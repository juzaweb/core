<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Juzaweb\Modules\Core\Facades\RouteResource;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::macro('admin', function (string $name, string $controller) {
            return RouteResource::admin($name, $controller);
        });

        Route::macro('api', function (string $name, string $controller) {
            return RouteResource::api($name, $controller);
        });

        $this->routes(function () {
            // Route::middleware('api')
            //     ->prefix('api/v1')
            //     ->group(__DIR__ . '/../routes/api.php');

            $adminPrefix = $this->app['config']->get('core.admin_prefix');
            Route::group([], function () {
                require __DIR__ . '/../routes/statics.php';
            });

            Route::middleware(['admin'])
                ->prefix($adminPrefix)
                ->group(__DIR__ . '/../routes/admin.php');

            Route::middleware(['theme'])
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
                \Juzaweb\Modules\Core\Http\Middleware\XFrameHeadersPolicy::class,
                \Juzaweb\Modules\Core\Http\Middleware\ContentSecurityPolicy::class,
                ...config('app.auth_middleware', []),
                'verified',
                \Juzaweb\Modules\Core\Http\Middleware\Admin::class,
                \Juzaweb\Modules\Core\Http\Middleware\CheckSetup::class,
                \Juzaweb\Modules\Core\Http\Middleware\ForceLocale::class,
            ]
        );

        $this->app['router']->middlewareGroup(
            'theme',
            [
                'web',
                \Juzaweb\Modules\Core\Http\Middleware\XFrameHeadersPolicy::class,
                \Juzaweb\Modules\Core\Http\Middleware\RedirectLanguage::class,
                \Juzaweb\Modules\Core\Http\Middleware\MultipleLanguage::class,
                \Juzaweb\Modules\Core\Http\Middleware\Theme::class,
            ]
        );
    }
}
