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
use Juzaweb\Core\Media\Contracts\ImageConversion;
use Juzaweb\Core\Media\Contracts\Media;
use Juzaweb\Core\Media\ImageConversionRepository;
use Juzaweb\Core\Media\MediaRepository;
use Juzaweb\Core\Rules\ModelExists;
use Juzaweb\Core\Rules\ModelUnique;
use Juzaweb\Core\Support;
use Juzaweb\Core\Contracts;

class CoreServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->customServices();
    }

    public function register(): void
    {
        $this->registerProviders();

        $this->registerServices();

        $this->registerPublishes();
    }

    protected function registerProviders(): void
    {
        $this->app->register(HookServiceProvider::class);
        $this->app->register(PermissionServiceProvider::class);
    }

    protected function registerServices(): void
    {
        $this->app->singleton(Contracts\GlobalData::class, function () {
            return new Support\GlobalDataRepository();
        });

        $this->app->singleton(Contracts\Setting::class, function ($app) {
            return new Support\SettingRepository($app['cache'], $app[Contracts\GlobalData::class]);
        });

        $this->app->singleton(Contracts\Breadcrumb::class, function () {
            return new Support\BreadcrumbFactory();
        });

        $this->app->singleton(Contracts\CacheGroup::class, fn ($app) => new Support\CacheGroupRepository($app['cache']));

        $this->app->singleton(ImageConversion::class, ImageConversionRepository::class);

        $this->app->singleton(Media::class, MediaRepository::class);

        $this->app->singleton(Contracts\Field::class, function ($app) {
            return new Support\FieldFactory();
        });

        $this->app->singleton(
            Contracts\RouteResource::class,
            fn ($app) => new Support\RouteResourceRepository($app->make('router'))
        );

        $this->app->singleton(
            Translation::class,
            function ($app) {
                return new TranslationRepository();
            }
        );
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
