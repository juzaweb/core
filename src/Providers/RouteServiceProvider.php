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
use Juzaweb\Core\Http\Middleware\Admin;

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

    public function register(): void
    {
        parent::register();

        $this->app['router']->middlewareGroup(
            'admin',
            [
                'web',
                ...config('core.auth_middleware', []),
                Admin::class,
                \Juzaweb\Core\Http\Middleware\ForceLocale::class,
            ]
        );

        $this->app['router']->middlewareGroup(
            'theme',
            [
                'web',
                \Juzaweb\Core\Http\Middleware\ForceLocale::class,
                \Juzaweb\Core\Http\Middleware\RedirectLanguage::class,
                \Juzaweb\Core\Http\Middleware\MultipleLanguage::class,
                \Juzaweb\Core\Http\Middleware\Theme::class,
            ]
        );
    }
}
