<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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

            Route::middleware(['admin'])
                ->prefix($adminPrefix)
                ->group(__DIR__ . '/../routes/admin.php');

            Route::middleware(['web'])
                ->group(__DIR__ . '/../routes/auth.php');

            Route::middleware(['web'])
                ->group(__DIR__ . '/../routes/web.php');
        });
    }
}
