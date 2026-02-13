<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     Larabiz Team <admin@larabiz.com>
 * @link       https://cms.juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Closure;

class Admin
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        // if ($user === null) {
        //     return redirect()->route('login')->withErrors(
        //         __('core::translation.you_cannot_access_this_page')
        //     );
        // }

        abort_unless($user->hasPermission(), 403, __('core::translation.you_cannot_access_this_page'));

        return $next($request);
    }
}
