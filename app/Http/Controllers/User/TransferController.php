<?php

namespace App\Http\Controllers\User;

use App\Bank;
use App\Mail\TransferMail;
use App\Mail\TransferSuccessMail;
use App\Ticket;
use App\Transfer;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TransferController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Solo los usuarios admin pueden hacer operaciones aparte de las basicas
        $this->middleware('admin', [
            'except' => [
                'index',
                'create',
                'store',
                'show',
            ]
        ]);

        // Los usuarios normales solo pueden visualizar sus propias recargas
        $this->middleware('owner.transfer', [
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
        DB::beginTransaction();

        $transfer = new Transfer($request->all());
        $transfer->user_id = Auth::user()->id;

        if (! empty($request->capture)) {

            $base64 = explode(',', $request->capture);

            $capture = base64_decode($base64[1]);
            $extension = str_replace('image/png', '', $base64[0]) !== $base64[0] ? '.png' : '.jpg';

            $path = Transfer::DIR_UPLOAD . Transfer::PREFIX_UPLOAD . time() . $extension;
            $transfer->capture = $path;

            if (! file_put_contents($path, $capture)) {
                DB::rollback();

                $this->sessionMessage('message.transfer.error', self::ALERT_DANGER);

                return new JsonResponse([
                    'success' => true,
                    'redirect' => route('transfer.create'),
                ]);
            }
        }

        $transfer->save();

        Mail::send(new TransferMail($transfer));

        DB::commit();

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

            Mail::send(new TransferSuccessMail($transfer));

            $this->sessionMessage('message.transfer.approved');
        } else {
            $this->sessionMessage('message.transfer.rejected', self::ALERT_DANGER);
        }
        DB::commit();

        return new JsonResponse(['success' => true, 'redirect' => route('transfer.show', ['transfer' => $transferId])]);
    }
}
