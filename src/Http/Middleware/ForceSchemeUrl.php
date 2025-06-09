<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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
