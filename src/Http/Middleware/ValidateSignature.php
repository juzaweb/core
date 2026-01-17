<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Routing\Middleware\ValidateSignature as BaseValidateSignature;
use Illuminate\Support\Facades\URL;

class ValidateSignature extends BaseValidateSignature
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
