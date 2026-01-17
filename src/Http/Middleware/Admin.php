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
use Illuminate\Support\Facades\View;
use Juzaweb\Modules\Core\Support\Actions;
use function Juzaweb\Modules\Admin\Http\Middleware\website;

class Admin
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        abort_if($user === null, 403, __('core::translation.you_cannot_access_this_page'));

        // Demo website, allow all users as website admin
        // But do not save any changes, see BlockDemoWebsiteActions middleware
        $demoSitePermission = website()?->isDemoWebsite()
            && website()->users()
                ->where('user_id', $user->id)
                ->exists();

        abort_unless($user->hasPermission() || $demoSitePermission, 403, __('core::translation.you_cannot_access_this_page'));

        do_action(Actions::MENU_INIT);

        View::composer('*', function ($view) use ($request) {
            $view->with('websiteId', $request->route('websiteId'));
        });

        return $next($request);
    }
}
