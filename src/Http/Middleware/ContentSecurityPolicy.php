<?php

namespace Juzaweb\Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! config('core.csp_script_src')) {
            return $next($request);
        }

        $scriptNonce = Str::random(32);

        $request->attributes->set('cspNonce', $scriptNonce);

        $response = $next($request);

        if (config('debugbar.enabled') || ($response->getStatusCode() >= 500 && config('app.debug'))) {
            return $response;
        }

        $scriptSrc = array_merge(
            [
                "'nonce-{$scriptNonce}'",
                "'self'",
                "'unsafe-eval'",
            ],
            config('core.csp_script_src')
        );

        $arr = [
            // "frame-ancestors 'none'",
            "script-src ".implode(" ", $scriptSrc)
        ];

        $response->headers->set('Content-Security-Policy', implode("; ", $arr));

        return $response;
    }
}
