<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Middleware;

use Illuminate\Auth\Middleware\EnsureEmailIsVerified as BaseEnsureEmailIsVerified;

class EnsureEmailIsVerified extends BaseEnsureEmailIsVerified
{
    public function handle($request, \Closure $next, $redirectToRoute = null)
    {
        if (! setting('user_verification', false)) {
            return $next($request);
        }

        return parent::handle($request, $next, $redirectToRoute);
    }
}
