<?php

namespace Juzaweb\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'Juzaweb\Core\Http\Controllers';

    public function map()
    {
        if (config('juzaweb.api_route')) {
            $this->mapApiRoutes();
        }

        $this->mapWebRoutes();
        $this->mapAdminRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../../routes/web.php');
    }

    protected function mapAdminRoutes()
    {
        Route::middleware('admin')
            ->prefix(config('juzaweb.admin_prefix'))
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../../routes/admin.php');
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(__DIR__ . '/../../routes/api.php');
    }
}
