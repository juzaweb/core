<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcom/larabiz
 * @author     Larabiz Team <admin@larabiz.com>
 * @link       https://larabiz.com
 * @license    MIT
 */

namespace Juzaweb\Core\Http\Middleware;

use Closure;
use Juzaweb\Core\Support\Actions;

class Admin
{
    public function handle($request, Closure $next)
    {
        abort_unless($user = $request->user(), 403, __('You cannot access this page.'));

        abort_unless($user->hasPermission(), 403, __('You cannot access this page.'));

        do_action(Actions::MENU_INIT);

        return $next($request);
    }
}
