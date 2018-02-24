<?php

namespace App\Http\Middleware;

use Closure;

class ForzeHttpsMiddleware
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
        if (
            env('APP_ENV') === 'production' &&
            boolval(env('APP_FORZE_HTTPS')) &&
            ! $request->isSecure()
        ) {

            return redirect(
                str_replace('http://', 'https://', $request->url())
            );
        }

        return $next($request);
    }
}
