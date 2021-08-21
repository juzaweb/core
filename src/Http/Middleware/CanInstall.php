<?php

namespace Juzaweb\Core\Http\Middleware;

use Closure;
use Juzaweb\Core\Helpers\Intaller;

class CanInstall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        if (Intaller::alreadyInstalled()) {
            return redirect()->home();
        }

        return $next($request);
    }
}
