<?php

namespace App\Http\Controllers\User;

use App\Animal;
use App\Bank;
use App\DailySort;
use App\Sort;
use App\Ticket;
use App\TicketDetail;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class TicketController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Los usuarios normales solo pueden visualizar sus propios tickets
        $this->middleware('owner.ticket', [
            'only' => ['show']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::orderBy('created_at', 'DESC');

        if (Auth::user()->level !== User::LEVEL_ADMIN) {
            $tickets->where('user_id', Auth::user()->id);
        }

        return view('user.ticket.index', ['tickets' => $tickets->paginate(20)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if (! isset($request->sort)) {
            $sorts = Sort::all();

            return view('user.ticket.create', ['sortList' => $sorts]);
        }

        $sort = Sort::where('slug', $request->sort)
            ->with('dailySorts')
            ->with('animals')
            ->first();

        if (! $sort) {
            $sorts = Sort::all();

            return view('user.ticket.create', ['sortList' => $sorts]);
        }


        $dailySortsArray = [];
        foreach ($sort->dailySorts as &$ds) {
            if ($ds->isOpen()) {
                $ds->time = $ds->timeFormat();
                $dailySortsArray[] = $ds;
            }
        }

        if (! count($dailySortsArray)) {
            $this->sessionMessage('message.sort.all.closed', self::ALERT_DANGER);

            return redirect()->route('user.index');
        }

        $animals = $this->getAnimalsWithDailyLimit($sort);

        return view('user.ticket.create', [
            'animals' => $animals,
            'sorts' => $dailySortsArray,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

            $ticket = new Ticket();
            $ticket->save();

            //  Asocia el ticket a los sorteos
            if (! $this->addSortsTicket($request->sorts, $ticket)) {
                DB::rollback();
                $this->sessionMessage('message.sort.closed', self::ALERT_DANGER);

                return new JsonResponse(['success' => true, 'redirect' => route('ticket.create')]);
            }

            //  Agrego los animales
            if (! $this->addAnimalsTicket($request->animals, $ticket)) {
                DB::rollback();
                $this->sessionMessage('message.animal.limit', self::ALERT_DANGER);

                return new JsonResponse(['success' => true, 'redirect' => route('ticket.create')]);
            }

            // Descuenta el balance del usuario
            $user = $ticket->user;
            $user->balance -= $ticket->total();
            $user->save();

        DB::commit();

        $this->sessionMessage('message.ticket.register');

        return new JsonResponse([
            'success' => true,
            'redirect' => route('ticket.show', ['ticket' => $ticket->id])
        ]);
    }

    /**
     * Agrega los animales a un ticket
     *
     * @param array $animals
     * @param Ticket $ticket
     * @return bool
     */
    private function addAnimalsTicket(array $animals, Ticket $ticket)
    {
        $animalsLimit = $this->getAnimalsWithDailyLimit($ticket->dailySorts[0]->sort);

        foreach ($animals as $animal) {
            $animalAux = Animal::find($animal['id']);

            if ($animalAux) {

                // Verifico que no exceda el limite diario
                foreach ($animalsLimit as $limit) {
                    if ($limit->id === $animalAux->id) {

                        if ((floatval($animal['amount']) * count($ticket->dailySorts)) > $limit->limit) {
                            return false;
                        }
                    }
                }

                $ticketDetail = new TicketDetail();
                $ticketDetail->ticket_id = $ticket->id;
                $ticketDetail->animal_id = $animalAux->id;
                $ticketDetail->amount = $animal['amount'] >= 1000 ? $animal['amount'] : 1000;
                $ticketDetail->save();
            }
        }

        return true;
    }

    /**
     * Asocia un ticket a los sorteos
     *
     * @param array $sorts
     * @param Ticket $ticket
     * @return bool
     */
    private function addSortsTicket(array $sorts, Ticket $ticket)
    {
        foreach ($sorts as $sort) {
            $dailySort = DailySort::find($sort['id']);

            // Verifica que no ha pasado la hora del sorteo
            if ($dailySort->isClose()) {
                return false;
            }

            $ticket->dailySorts()->attach($dailySort->id, [
                'pay_per_100' => $dailySort->sort->pay_per_100,
            ]);
        }

        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        $banks = Bank::all();

        return view('user.ticket.show', compact('ticket', 'banks'));
    }

    /**
     * Obtiene todos los animalitos y calcula el limite diario
     * menos lo consumido durante el dia por este usuario
     *
     * @param $sort
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getAnimalsWithDailyLimit($sort)
    {
        $animals = $sort->animals;
        $start = (new \DateTime())->setTime(00, 00, 00);
        $end = (new \DateTime())->setTime(23, 59, 59);
        $sells = [];

        // Obtengo todos los tickets de hoy para este usuario //TODO y este sorteo
        $tickets = Ticket::where('user_id', Auth::user()->id)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)
            ->get()
        ;

        // Calculo las ventas de cada animalito
        foreach ($tickets as $ticket) {
            foreach ($ticket->ticketDetails as $detail) {

                if (! isset($sells[ $detail->animal->id ])) {
                    $sells[ $detail->animal->id ] = 0;
                }

                $sells[ $detail->animal->id ] += ($detail->amount * count($ticket->dailySorts));
            }
        }

        // Agrega el limite a cada animalito
        foreach ($animals as $animal) {
            $animal->limit = isset($sells[$animal->id]) ? $sort->top_sell - $sells[$animal->id] : $sort->top_sell;
        }

        return $animals;
    }
}
