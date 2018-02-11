<?php

namespace App\Http\Controllers\User;

use App\Animal;
use App\Bank;
use App\DailySort;
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $now = (new \DateTime())->modify('+5 minutes');
        $dailySorts = DailySort::where('time', '>', $now)->get();

        if (! count($dailySorts)) {
            $this->sessionMessage('message.sort.all.closed', self::ALERT_DANGER);

            return redirect()->route('user.index');
        }

        $animals = Animal::all();

        foreach ($dailySorts as &$ds) {
            $ds->time = $ds->timeFormat();
        }

        return view('user.ticket.create', [
            'animals' => $animals,
            'sorts' => $dailySorts,
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

            //  Agrego los animales
            $this->addAnimalsTicket($request->animals, $ticket);

            //  Asocia el ticket a los sorteos
            if (! $this->addSortsTicket($request->sorts, $ticket)) {
                DB::rollback();
                $this->sessionMessage('message.sort.closed', self::ALERT_DANGER);

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
     */
    private function addAnimalsTicket(array $animals, Ticket $ticket)
    {
        foreach ($animals as $animal) {
            $animalAux = Animal::where('code', $animal['code'])->first();

            if ($animalAux) {
                $ticketDetail = new TicketDetail();
                $ticketDetail->ticket_id = $ticket->id;
                $ticketDetail->animal_id = $animalAux->id;
                $ticketDetail->amount = $animal['amount'] >= 300 ? $animal['amount'] : 300;
                $ticketDetail->save();
            }
        }
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
