<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\User;
use App\Withdraw;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OwnerWithdrawMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $withdraw = Withdraw::find($request->withdraw);
        $user = Auth::user();

        if ($user->level !== User::LEVEL_ADMIN) {
            // Si el usuario no es ADMIN, verifico que sea el propietario del ticket

            if (! $withdraw || $withdraw->user->id !== $user->id) {
                Session::flash('lotto.alert.message', 'Retiro no existe');
                Session::flash('lotto.alert.type', Controller::ALERT_DANGER);

                return redirect()->route('withdraw.index');
            }
        }

        return $next($request);
    }
}
