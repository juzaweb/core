<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Juzaweb\Modules\Core\Facades\RouteResource;
use Juzaweb\Modules\Core\Http\Middleware\Admin;
use Juzaweb\Modules\Core\Http\Middleware\CheckSetup;
use Juzaweb\Modules\Core\Http\Middleware\ContentSecurityPolicy;
use Juzaweb\Modules\Core\Http\Middleware\ForceLocale;
use Juzaweb\Modules\Core\Http\Middleware\MultipleLanguage;
use Juzaweb\Modules\Core\Http\Middleware\RedirectLanguage;
use Juzaweb\Modules\Core\Http\Middleware\Theme;
use Juzaweb\Modules\Core\Http\Middleware\XFrameHeadersPolicy;

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
                require __DIR__.'/../routes/statics.php';
            });

            Route::middleware(['admin'])
                ->prefix($adminPrefix)
                ->group(__DIR__.'/../routes/admin.php');

            Route::middleware(['theme'])
                ->group(__DIR__.'/../routes/web.php');
        });
    }

    public function register(): void
    {
        parent::register();

        $this->app['router']->middlewareGroup(
            'admin',
            [
                'web',
                XFrameHeadersPolicy::class,
                ContentSecurityPolicy::class,
                'auth',
                'verified',
                Admin::class,
                CheckSetup::class,
                ForceLocale::class,
            ]
        );

        $this->app['router']->middlewareGroup(
            'theme',
            [
                'web',
                XFrameHeadersPolicy::class,
                RedirectLanguage::class,
                MultipleLanguage::class,
                Theme::class,
            ]
        );
    }
}
