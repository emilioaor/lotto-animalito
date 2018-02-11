<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthMiddleware
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
        if (! Auth::check()) {

            if ($request->isXmlHttpRequest()) {
                return new JsonResponse('Unauthorized', 401);
            }

            Session::flash('lotto.alert.message', 'Debe iniciar sesión');
            Session::flash('lotto.alert.type', Controller::ALERT_DANGER);

            return redirect()->route('index.index');
        }

        return $next($request);
    }
}
