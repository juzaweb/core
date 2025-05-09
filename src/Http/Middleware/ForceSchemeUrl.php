<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Middleware;

use Illuminate\Support\Facades\URL;

class ForceSchemeUrl
{
    public function handle($request, $next)
    {
        if (($proto = $request->headers->get('X-Forwarded-Proto')) && ! $request->secure()) {
            URL::forceScheme($proto);
        }

        return $next($request);
    }
}
