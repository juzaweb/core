<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Facades\Theme;

class CheckSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip check for setup routes to prevent infinite redirect
        if ($request->routeIs('admin.setup') || $request->routeIs('admin.setup.process')) {
            return $next($request);
        }

        // Check if theme setup has been completed
        if (Theme::current() && !theme_setting('setup')) {
            return redirect()->route('admin.setup');
        }

        return $next($request);
    }
}
