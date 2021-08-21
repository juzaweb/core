<?php

namespace Juzaweb\Core\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Juzaweb\Core\Console\Commands\InstallCommand;
use Juzaweb\Core\Http\Middleware\CanInstall;
use Juzaweb\Core\Http\Middleware\Installed;

class InstallerServiceProvider extends ServiceProvider
{
    public function register()
    {
        //$this->publishFiles();

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/installer.php',
            'installer'
        );

        $this->commands([
            InstallCommand::class,
        ]);
    }

    /**
     * Bootstrap the application events.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('install', CanInstall::class);
        $router->pushMiddlewareToGroup('theme', Installed::class);
        //$this->registerViews();
    }

    /**
     * Publish config file for the installer.
     *
     * @return void
     */
    protected function publishFiles()
    {
        $this->publishes([
            __DIR__ . '/../../config/installer.php' => base_path('config/installer.php'),
        ], 'installer_config');
    }

    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'installer');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'installer');
    }
}
