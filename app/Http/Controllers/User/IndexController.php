<?php

namespace App\Http\Controllers\User;

use App\Animal;
use App\Bank;
use App\DailySort;
use App\Result;
use App\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{

    /**
     * Vista principal de usuarios
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $sorts = DailySort::all();

        return view('user.index', ['sorts' => $sorts]);
    }

    /**
     * Vista de configuracion del usuario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function config()
    {
        $banks = Bank::orderBy('name')->get();

        return view('user.config', ['banks' => $banks]);
    }

    /**
     * Actualiza los datos bancarios del usuario
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bankUpdate(Request $request)
    {
        $user = Auth::user();
        $user->bank_id = $request->bank_id;
        $user->number_account = $request->number_account;
        $user->name = $request->name;
        $user->identity_card = $request->identity_card;
        $user->save();

        return new JsonResponse(['success' => true,]);
    }

    /**
     * Cambia la contraseña del usuario
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        if (! Hash::check($request->current_password, $user->password)) {
            return new JsonResponse([
                'success' => false,
                'currentPasswordInvalid' => true,
            ]);
        }

        if ($request->new_password !== $request->new_password_confirmation) {
            throw new \Exception('Password no match');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return new JsonResponse(['success' => true]);
    }

    /**
     * Resultados de los sorteos
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function results(Request $request)
    {
        if (isset($request->date)) {
            $date = \DateTime::createFromFormat('Y-m-d', $request->date);
        } else {
            $date = new \DateTime();
        }

        if ($date > ($now = new \DateTime())) {
            $this->sessionMessage('message.date.noFuture', self::ALERT_DANGER);

            return redirect()->route('user.results', ['date' => $now->format('Y-m-d')]);
        }

        $sorts = DailySort::orderBy('time')->get();
        $results = Result::where('date', $date->format('Y-m-d'))->get();
        $animals = Animal::all();

        foreach ($sorts as &$sort) {
            $sort->timeFormat = $sort->timeFormat();
            $sort->isOpen = $sort->isOpen($date);
        }

        return view('user.results', [
            'sorts' => $sorts,
            'results' => $results,
            'date' => $date->format('Y-m-d'),
            'animals' => $animals,
        ]);
    }

    /**
     * Establece el animal ganador del sorteo
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setGain(Request $request)
    {
        // Verifico que el sorteo ya este cerrado para poder asignar al ganador
        $now = new \DateTime();
        $dailySort = DailySort::find($request->sort_id);
        $sortTime = \DateTime::createFromFormat('H:i:s', $dailySort->time);

        if ($now < $sortTime) {
            $this->sessionMessage('message.sort.no.close', self::ALERT_DANGER);

            return new JsonResponse(['success' => true, 'redirect' => route('user.results')]);
        }

        DB::beginTransaction();

        $result = Result::where('date', $request->date)
            ->where('daily_sort_id', $request->sort_id)
            ->firstOrNew([
                'date' => $request->date,
                'daily_sort_id' => $request->sort_id,
            ]);

        if ($result->animal) {
            // Si ya tiene ganador asociado se reversan los premios
            $this->reversePrize($request->sort_id, $result->animal->id, $request->date);
        }

        $result->animal_id = $request->animal_id;
        $result->save();

        // Se pagan los premios a los ganadores
        $this->payPrize($request->sort_id, $result->animal_id, $request->date);

        DB::commit();

        $this->sessionMessage('message.gain.register');

        return new JsonResponse([
            'success' => true,
            'redirect' => route('user.results', [
                'date' => isset($request->date) ? $request->date : null
            ]),
        ]);
    }

    /**
     * Reversa los premios pagados a un sorteo
     *
     * @param $sortId
     * @param $animalId
     * @param $date
     */
    private function reversePrize($sortId, $animalId, $date)
    {
        $dailySort = DailySort::find($sortId);

        $start = \DateTime::createFromFormat('Y-m-d', $date)->setTime(0,0,0);
        $end = \DateTime::createFromFormat('Y-m-d', $date)->setTime(23,59,59);

        // Recorro todos los tickets jugados para este sorteo
        foreach ($dailySort->tickets as $ticket) {
                // Compruebo que el ticket sea de la fecha
            if ($ticket->created_at >= $start && $ticket->created_at <= $end) {
                // Recorro todos los animalitos jugados en el ticket
                foreach ($ticket->ticketDetails as $detail) {
                    // Si la jugada es por el ganador, se reversa el premio
                    if ($detail->animal_id == $animalId) {
                        $user = $ticket->user;
                        $pay_per_100 = $ticket->pivot->pay_per_100;
                        $prize = ($detail->amount / 100) * $pay_per_100;
                        $user->balance -= $prize;
                        $user->save();
                    }
                }
            }
        }
    }

    /**
     * Paga los premios a los ganadores del sorteo
     *
     * @param $sortId
     * @param $animalId
     * @param $date
     */
    private function payPrize($sortId, $animalId, $date)
    {
        $dailySort = DailySort::find($sortId);

        $start = \DateTime::createFromFormat('Y-m-d', $date)->setTime(0,0,0);
        $end = \DateTime::createFromFormat('Y-m-d', $date)->setTime(23,59,59);

        // Recorro todos los tickets jugados para este sorteo
        foreach ($dailySort->tickets as $ticket) {
            // Compruebo que el ticket este activo y sea de la fecha
            if ($ticket->status === Ticket::STATUS_ACTIVE && $ticket->created_at >= $start && $ticket->created_at <= $end) {
                // Recorro todos los animalitos jugados en el ticket
                foreach ($ticket->ticketDetails as $detail) {
                    // Si la jugada es por el ganador, se paga el premio
                    if ($detail->animal_id == $animalId) {
                        $user = $ticket->user;
                        $pay_per_100 = $ticket->pivot->pay_per_100;
                        $prize = ($detail->amount / 100) * $pay_per_100;
                        $user->balance += $prize;
                        $user->save();
                    }
                }
            }
        }
    }

    /**
     * Retorna la data de la grafica
     *
     * @return JsonResponse
     */
    public function graphicData()
    {
        return new JsonResponse(Auth::user()->graphicData());
    }
}
