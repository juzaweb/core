<?php
/**
 *
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/25/2021
 * Time: 9:53 PM
 */

namespace Juzaweb\Core\Providers;

use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Core\Console\Commands\UpdateCommand;
use Juzaweb\Core\Helpers\HookAction;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootMigrations();
        $this->bootPublishes();
        $this->loadFactoriesFrom(__DIR__ . '/../database/factories');

        Validator::extend('recaptcha', 'Juzaweb\Core\Validators\Recaptcha@validate');
        Schema::defaultStringLength(150);
    }

    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);

            if (config('app.debug')) {
                $this->app->register(DebugbarServiceProvider::class);
            }
        }

        $this->registerProviders();
        $this->registerSingleton();
        $this->mergeConfigFrom(__DIR__ . '/../../config/juzaweb.php', 'juzaweb');
        $this->mergeConfigFrom(__DIR__ . '/../../config/locales.php', 'locales');

        $this->commands([
            UpdateCommand::class,
        ]);
    }

    protected function bootMigrations()
    {
        $mainPath = __DIR__ . '/../database/migrations';
        $this->loadMigrationsFrom($mainPath);
    }

    protected function bootPublishes()
    {
        $this->publishes([
            __DIR__ . '/../../config/juzaweb.php' => base_path('config/juzaweb.php'),
            __DIR__ . '/../../config/locales.php' => base_path('config/locales.php'),
        ], 'juzaweb_config');
    }

    protected function registerProviders()
    {
        $this->app->register(BackendServiceProvider::class);
        $this->app->register(DbConfigServiceProvider::class);
        $this->app->register(HookActionServiceProvider::class);
        $this->app->register(PerformanceServiceProvider::class);
        $this->app->register(FilemanagerServiceProvider::class);
        $this->app->register(HooksServiceProvider::class);
        $this->app->register(HookBladeServiceProvider::class);
        $this->app->register(PostTypeServiceProvider::class);
        $this->app->register(InstallerServiceProvider::class);
        //$this->app->register(SwaggerServiceProvider::class);
    }

    protected function registerSingleton()
    {
        $this->app->singleton('juzaweb.hook', function () {
            return new HookAction();
        });
    }
}