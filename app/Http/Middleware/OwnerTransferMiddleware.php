<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Transfer;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OwnerTransferMiddleware
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
        $transfer = Transfer::find($request->transfer);
        $user = Auth::user();

        if ($user->level !== User::LEVEL_ADMIN) {
            // Si el usuario no es ADMIN, verifico que sea el propietario del ticket

            if (! $transfer || $transfer->user->id !== $user->id) {
                Session::flash('lotto.alert.message', 'Recarga no existe');
                Session::flash('lotto.alert.type', Controller::ALERT_DANGER);

                return redirect()->route('transfer.index');
            }
        }

        return $next($request);
    }
}
