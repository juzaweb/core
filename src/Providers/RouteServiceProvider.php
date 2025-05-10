<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->routes(function () {
            // Route::middleware('api')
            //     ->prefix('api/v1')
            //     ->group(__DIR__ . '/../routes/api.php');

            $adminPrefix = $this->app['config']->get('core.admin_prefix');

            Route::middleware([
                'web',
                ...config('core.auth_middleware', []),
                'admin',
            ])
                ->prefix($adminPrefix)
                ->group(__DIR__ . '/../routes/admin.php');

            Route::middleware(['web', 'guest'])
                ->group(__DIR__ . '/../routes/auth.php');

            Route::middleware(['web'])
                ->group(__DIR__ . '/../routes/web.php');
        });
    }
}
