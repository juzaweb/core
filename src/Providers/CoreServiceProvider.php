<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Providers;

use App\Providers\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Juzaweb\Core\Rules\ModelExists;
use Juzaweb\Core\Rules\ModelUnique;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->customServices();
    }

    public function register(): void
    {
        $this->registerServices();

        $this->registerPublishes();
    }

    protected function registerServices()
    {
        $this->app->register(HookServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
    }

    protected function registerPublishes(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');

        $this->publishes([
            __DIR__ . '/../../config/core.php' => config_path('core.php'),
            __DIR__ . '/../../config/modules.php' => config_path('modules.php'),
            __DIR__ . '/../../config/media.php' => config_path('media.php'),
            __DIR__ . '/../../config/notification.php' => config_path('notification.php'),
        ], 'core-config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/core'),
        ], 'core-lang');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/core'),
        ], 'core-views');

        $this->publishes([
            __DIR__ . '/../../assets' => public_path(),
        ], 'core-assets');
    }

    protected function customServices(): void
    {
        Validator::extend(
            'recaptcha',
            '\Juzaweb\Core\Rules\ReCaptchaValidator@validate'
        );

        Validator::extend(
            'domain',
            '\Juzaweb\Core\Rules\DomainValidator@validate'
        );

        Rule::macro(
            'modelExists',
            function (
                string $modelClass,
                string $modelAttribute = 'id',
                ?callable $callback = null
            ) {
                return new ModelExists($modelClass, $modelAttribute, $callback);
            }
        );

        Rule::macro(
            'modelUnique',
            function (
                string $modelClass,
                string $modelAttribute = 'id',
                ?callable $callback = null
            ) {
                return new ModelUnique($modelClass, $modelAttribute, $callback);
            }
        );
    }
}
