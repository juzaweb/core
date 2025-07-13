<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Juzaweb\Core\Commands;
use Juzaweb\Core\Contracts;
use Juzaweb\Core\DataTables\HtmlBuilder;
use Juzaweb\Core\Facades\Menu;
use Juzaweb\Core\Facades\Setting;
use Juzaweb\Core\Http\Middleware\Admin;
use Juzaweb\Core\Http\Middleware\ValidateSignature;
use Juzaweb\Core\Models\User;
use Juzaweb\Core\Modules\Providers\ModulesServiceProvider;
use Juzaweb\Core\Rules\ModelExists;
use Juzaweb\Core\Rules\ModelUnique;
use Juzaweb\Core\Support;
use Juzaweb\Hooks\Contracts\Hook;

class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->customServices();

        $this->registerCommands();

        $this->app['router']->aliasMiddleware('admin', Admin::class);
        $this->app['router']->aliasMiddleware('signed', ValidateSignature::class);

        Carbon::macro('toUserTimezone', function () {
            $tz = auth()->user()?->timezone ?? config('app.timezone');
            return $this->copy()->setTimezone($tz);
        });

        $this->app->bind('datatables.html', fn () => $this->app->make(HtmlBuilder::class));

        // Before check user permission
        Gate::before(
            function ($user, $ability) {
                // Super admin has all permission
                /** @var User $user */
                if ($user->hasRoleAllPermissions()) {
                    return true;
                }

                if ($user->isBanned()) {
                    return false;
                }
            }
        );

        $this->registerSettings();
        $this->registerMenus();
    }

    public function register(): void
    {
        $this->registerProviders();

        $this->registerServices();

        $this->registerPublishes();
    }

    protected function registerProviders(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ModulesServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
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

        $this->app->singleton(Contracts\Field::class, function ($app) {
            return new Support\FieldFactory();
        });

        $this->app->singleton(
            Contracts\RouteResource::class,
            fn ($app) => new Support\RouteResourceRepository($app->make('router'))
        );

        $this->app->singleton(
            Contracts\Menu::class,
            fn ($app) => new Support\MenuRepository(
                $app[Contracts\GlobalData::class],
                $app[Hook::class]
            )
        );
    }

    protected function registerPublishes(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'core');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'core');

        $this->mergeConfigFrom(__DIR__ . '/../../config/core.php', 'core');

        $this->mergeConfigFrom(__DIR__ . '/../../config/modules.php', 'modules');

        $this->mergeConfigFrom(__DIR__ . '/../../config/themes.php', 'themes');

        $this->publishes([
            __DIR__ . '/../../config/core.php' => config_path('core.php'),
            __DIR__ . '/../../config/modules.php' => config_path('modules.php'),
            __DIR__ . '/../../config/themes.php' => config_path('themes.php'),
        ], 'core-config');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/core'),
        ], 'core-lang');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/core'),
        ], 'core-views');

        $this->publishes([
            __DIR__ . '/../../assets/public' => public_path('vendor/core'),
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

    protected function registerCommands(): void
    {
        $this->commands([
            Commands\MakeUserCommand::class,
            Commands\CacheSizeCommand::class,
            Commands\TestMailCommand::class,
        ]);
    }

    protected function registerMenus(): void
    {
        $this->booted(
            function () {
                Menu::make('dashboard', __('Dashboard'))
                    ->icon('fa-tachometer-alt');

                Menu::make('pages', __('Pages'))
                    ->icon('fa-file-alt');

                Menu::make('settings', __('Settings'))
                    ->icon('fa-cogs')
                    ->priority(99);

                Menu::make('general', __('General'))
                    ->url('settings/general')
                    ->parent('settings');

                Menu::make('social-login', __('Social Login'))
                    ->url('settings/social-login')
                    ->parent('settings');

                // Menu::make('roles', __('Roles'))
                //     ->parent('settings');

                Menu::make('users', __('Users'))
                    ->parent('settings');

                Menu::make('languages', __('Languages'))
                    ->parent('settings');
            }
        );
    }

    protected function registerSettings(): void
    {
        $this->booted(
            function () {
                Setting::make('title')
                    ->default(config('app.name'));

                Setting::make('description');
                Setting::make('sitename');

                Setting::make('logo');
                Setting::make('favicon');
                Setting::make('banner');

                Setting::make('user_registration')
                    ->default(true);

                Setting::make('user_verification')
                    ->default(false);

                // Social Login Settings
                $drivers = array_keys(config('core.social_login.providers', []));

                foreach ($drivers as $driver) {
                    Setting::make("{$driver}_login")
                        ->add();

                    Setting::make("{$driver}_client_id")
                        ->add();

                    Setting::make("{$driver}_client_secret")
                        ->add();
                }
            }
        );
    }
}
