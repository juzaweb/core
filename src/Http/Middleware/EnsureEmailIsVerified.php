<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Illuminate\Auth\Middleware\EnsureEmailIsVerified as BaseEnsureEmailIsVerified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Admin\Networks\Facades\Network;

class EnsureEmailIsVerified extends BaseEnsureEmailIsVerified
{
    public function handle($request, \Closure $next, $redirectToRoute = null)
    {
        if (! setting('user_verification', false)) {
            return $next($request);
        }

        return parent::handle($request, $next, $redirectToRoute);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handleEnsureEmailVerified($request, \Closure $next, $redirectToRoute = null)
    {
        //  &&
        // ! $request->is('/verification/resend')
        //
        $user = $request->user();

        if (! $user || ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail())) {

            if (!$redirectToRoute) {
                $redirectToRoute = $user instanceof User ? 'user.verification.notice' : 'verification.notice';
            }

            return $request->expectsJson()
                    ? abort(403, 'Your email address is not verified.')
                    : Redirect::guest(URL::route($redirectToRoute ?: 'verification.notice'));
        }

        return $next($request);
    }
}
