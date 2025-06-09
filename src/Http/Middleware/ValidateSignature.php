<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;

class ValidateSignature extends \Illuminate\Routing\Middleware\ValidateSignature
{
    public function handle($request, Closure $next, ...$args)
    {
        // Force Scheme Url
        if (($proto = $request->headers->get('X-Forwarded-Proto')) && ! $request->secure()) {
            URL::forceScheme($proto);
            $request->server->set('HTTPS','on');
        }

        return parent::handle($request, $next, ...$args);
    }
}
