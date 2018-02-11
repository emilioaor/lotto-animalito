<?php

namespace App\Http\Controllers\User;

use App\Bank;
use App\Ticket;
use App\Transfer;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfers = Transfer::orderBy('transfers.created_at', 'DESC');

        if (Auth::user()->level !== User::LEVEL_ADMIN) {
            $transfers->where('user_id', Auth::user()->id);
        }

        return view('user.transfer.index', ['transfers' => $transfers->paginate(20)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = Bank::orderBy('name')->get();

        return view('user.transfer.create', ['banks' => $banks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transfer = new Transfer($request->all());
        $transfer->user_id = Auth::user()->id;
        $transfer->save();

        $this->sessionMessage('message.transfer.register');

        return new JsonResponse([
            'success' => true,
            'redirect' => route('transfer.show', ['transfer' => $transfer->id]),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transfer = Transfer::find($id);

        return view('user.transfer.show', ['transfer' => $transfer]);
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

    /**
     * Aprueba o cancela una recarga
     *
     * @param Request $request
     * @param $transferId
     * @param $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus(Request $request, $transferId, $status)
    {
        DB::beginTransaction();

        // Actualiza el estatus de la transferencia
        $transfer = Transfer::find($transferId);
        $transfer->status = $status;
        $transfer->approved = $request->amount;
        $transfer->comment = $request->comment;
        $transfer->save();

        if (intval($status) === Transfer::STATUS_ACCEPTED) {

            // En caso de ser aprobada incrementa el saldo del usuario
            $user = $transfer->user;
            $user->balance += $transfer->approved;
            $user->save();

            $this->sessionMessage('message.transfer.approved');
        } else {
            $this->sessionMessage('message.transfer.rejected', self::ALERT_DANGER);
        }
        DB::commit();

        return new JsonResponse(['success' => true, 'redirect' => route('transfer.show', ['transfer' => $transferId])]);
    }
}
