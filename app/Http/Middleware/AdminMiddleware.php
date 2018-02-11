<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminMiddleware
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
        if (Auth::user()->level !== User::LEVEL_ADMIN) {

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse('Unauthorized', 401);
            }

            Session::flash('lotto.alert.message', 'No tiene permisos suficientes');
            Session::flash('lotto.alert.type', Controller::ALERT_DANGER);

            return redirect()->route('user.index');
        }

        return $next($request);
    }
}
