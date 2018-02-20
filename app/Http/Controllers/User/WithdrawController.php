<?php

namespace App\Http\Controllers\User;

use App\Mail\WithdrawMail;
use App\Mail\WithdrawSuccessMail;
use App\Service\EmailService;
use App\User;
use App\Withdraw;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class WithdrawController extends Controller
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

        // Los usuarios normales solo pueden visualizar sus propios retiros
        $this->middleware('owner.withdraw', [
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
        $withdraws = Withdraw::orderBy('created_at', 'DESC');

        if (Auth::user()->level !== User::LEVEL_ADMIN) {
            $withdraws->where('user_id', Auth::user()->id);
        }

        return view('user.withdraw.index', ['withdraws' => $withdraws->paginate(20)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.withdraw.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Hash::check($request->password, Auth::user()->password)) {
            return new JsonResponse([
                'success' => false,
                'passwordInvalid' => true,
            ]);
        }

        DB::beginTransaction();
            //  Registra el retiro
            $withdraw = new Withdraw($request->all());
            $withdraw->save();

            //  Actualiza los saldos del usuario
            $user = Auth::user();
            $user->block_balance += $withdraw->amount;
            $user->save();

            // Notifica a los administradores
            $user->generateAdminNotification(
                Lang::trans('message.withdraw.admin.notification', [
                    'amount' => number_format($withdraw->amount, 2, ',', '.')
                ]),
                route('withdraw.show', ['withdraw' => $withdraw->id])
            );

            EmailService::addEmail(
                WithdrawMail::class,
                [
                    env('APP_MY_EMAIL'),
                    env('APP_MY_EMAIL2')
                ],
                ['id' => $withdraw->id]
            );

        DB::commit();

        $this->sessionMessage('message.withdraw.create');

        return new JsonResponse([
            'success' => true,
            'redirect' => route('withdraw.show', ['withdraw' => $withdraw->id]),
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
        $withdraw = Withdraw::find($id);

        return view('user.withdraw.show', ['withdraw' => $withdraw]);
    }

    /**
     * Actualiza el estatus del retiro
     *
     * @param $id
     * @param $status
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id, $status, Request $request)
    {
        if (! isset($request->password) || ! Hash::check($request->password, Auth::user()->password)) {
            $this->sessionMessage('message.password.fail', self::ALERT_DANGER);

            return new JsonResponse([
                'success' => true,
                'redirect' => route('withdraw.show', ['withdraw' => $id])
            ]);
        }
        DB::beginTransaction();

        // Cambia el estatus del retiro
        $withdraw = Withdraw::find($id);
        $withdraw->status = $status;

        // Actualiza el saldo del usuario
        $user = $withdraw->user;
        $user->block_balance = $user->block_balance - $withdraw->amount;
        $user->balance = $user->balance - $withdraw->amount;
        $user->save();

        $user->generateNotification(
            Lang::trans('message.withdraw.notification', [
                'amount' => number_format($withdraw->amount, 2, ',', '.')
            ]),
            route('withdraw.show', ['withdraw' => $id])
        );

        if (! empty($request->capture)) {

            $base64 = explode(',', $request->capture);

            $capture = base64_decode($base64[1]);
            $extension = str_replace('image/png', '', $base64[0]) !== $base64[0] ? '.png' : '.jpg';

            $path = Withdraw::DIR_UPLOAD . Withdraw::PREFIX_UPLOAD . time() . $extension;
            $withdraw->capture = $path;

            if (! file_put_contents($path, $capture)) {
                DB::rollback();

                $this->sessionMessage('message.withdraw.error', self::ALERT_DANGER);

                return new JsonResponse([
                    'success' => true,
                    'redirect' => route('withdraw.show', ['withdraw' => $id]),
                ]);
            }
        }

        $withdraw->save();

        EmailService::addEmail(
            WithdrawSuccessMail::class,
            [$user->email],
            ['id' => $withdraw->id]
        );

        DB::commit();

        $this->sessionMessage('message.withdraw.success');

        return new JsonResponse([
            'success' => true,
            'redirect' => route('withdraw.show', ['withdraw' => $id])
        ]);
    }
}
