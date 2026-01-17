<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Providers;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Juzaweb\Hooks\Contracts\Hook;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Core\Contracts\Breadcrumb;
use Juzaweb\Modules\Core\Contracts\Chart;
use Juzaweb\Modules\Core\Contracts\Field;
use Juzaweb\Modules\Core\Contracts\GlobalData;
use Juzaweb\Modules\Core\Contracts\Locale;
use Juzaweb\Modules\Core\Contracts\Menu;
use Juzaweb\Modules\Core\Contracts\MenuBox;
use Juzaweb\Modules\Core\Contracts\NavMenu;
use Juzaweb\Modules\Core\Contracts\PageBlock;
use Juzaweb\Modules\Core\Contracts\PageTemplate;
use Juzaweb\Modules\Core\Contracts\RouteResource;
use Juzaweb\Modules\Core\Contracts\Setting;
use Juzaweb\Modules\Core\Contracts\Sidebar;
use Juzaweb\Modules\Core\Contracts\Sitemap;
use Juzaweb\Modules\Core\Contracts\Theme;
use Juzaweb\Modules\Core\Contracts\ThemeSetting;
use Juzaweb\Modules\Core\Contracts\Thumbnail;
use Juzaweb\Modules\Core\Contracts\Widget;
use Juzaweb\Modules\Core\DataTables\HtmlBuilder;
use Juzaweb\Modules\Core\FileManager\Providers\FileManagerServiceProvider;
use Juzaweb\Modules\Core\Modules\Providers\ModulesServiceProvider;
use Juzaweb\Modules\Core\Rules\ModelExists;
use Juzaweb\Modules\Core\Rules\ModelUnique;
use Juzaweb\Modules\Core\Support\BreadcrumbFactory;
use Juzaweb\Modules\Core\Support\ChartRepository;
use Juzaweb\Modules\Core\Support\FieldFactory;
use Juzaweb\Modules\Core\Support\GlobalDataRepository;
use Juzaweb\Modules\Core\Support\LocaleRepository;
use Juzaweb\Modules\Core\Support\MenuBoxRepository;
use Juzaweb\Modules\Core\Support\MenuRepository;
use Juzaweb\Modules\Core\Support\NavMenuRepository;
use Juzaweb\Modules\Core\Support\PageBlockRepository;
use Juzaweb\Modules\Core\Support\PageTemplateRepository;
use Juzaweb\Modules\Core\Support\RouteResourceRepository;
use Juzaweb\Modules\Core\Support\SettingRepository;
use Juzaweb\Modules\Core\Support\SidebarRepository;
use Juzaweb\Modules\Core\Support\SitemapRepository;
use Juzaweb\Modules\Core\Support\ThemeSettingRepository;
use Juzaweb\Modules\Core\Support\ThumbnailRepository;
use Juzaweb\Modules\Core\Support\WidgetRepository;
use Juzaweb\Modules\Core\View\Components\Card;
use Juzaweb\Modules\Core\View\Components\CookieConsent;
use Juzaweb\Modules\Core\View\Components\Form;
use Juzaweb\Modules\Core\View\Components\JsVar;
use Juzaweb\Modules\Core\View\Components\LanguageCard;
use Juzaweb\Modules\Core\View\Components\Repeater;
use Juzaweb\Modules\Core\View\Components\SeoMeta;
use Juzaweb\Modules\Core\View\Components\ThemeInit;
use Juzaweb\Modules\Core\View\Components\ThemeJsVar;

class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->customServices();
        $this->registerComponents();

        $this->app->bind(
            'datatables.html',
            fn() => $this->app->make(HtmlBuilder::class)
        );

        // Before check user permission
        Gate::before(
            function ($user, $ability) {
                // Super admin of system has all permission
                if ($user->isSuperAdmin()) {
                    return true;
                }

                // Supper admin of website has all permission of website
                if ($user->isWebsiteAdmin()) {
                    return true;
                }

                // Super admin has all permission
                /** @var User $user */
                if ($user->hasRoleAllPermissions()) {
                    return true;
                }

                // Banned user cannot access any permission
                if ($user->isBanned()) {
                    return false;
                }
            }
        );
    }

    public function register(): void
    {
        $this->registerProviders();

        $this->registerServices();

        $this->registerConfigs();

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->registerTranslations();
        $this->registerViews();

        $this->app->singleton(
            Locale::class,
            fn($app) => new LocaleRepository($app)
        );
    }

    protected function registerConfigs(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/core.php',
            'core'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/media.php',
            'media'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/modules.php',
            'modules'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/themes.php',
            'themes'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/translator.php',
            'translator'
        );
    }

    protected function registerProviders(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(PerformanceServiceProvider::class);
        $this->app->register(HelperServiceProvider::class);
        $this->app->register(FileManagerServiceProvider::class);
        $this->app->register(ModulesServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
        // $this->app->register(AdminServiceProvider::class);
    }

    protected function registerServices(): void
    {
        $this->app->singleton(GlobalData::class, function () {
            return new GlobalDataRepository();
        });

        $this->app->singleton(Setting::class, function ($app) {
            return new SettingRepository($app['cache'], $app[GlobalData::class]);
        });

        $this->app->singleton(
            ThemeSetting::class,
            function ($app) {
                return new ThemeSettingRepository(
                    $app['cache'],
                    $app[GlobalData::class],
                    $app[Theme::class]
                );
            }
        );

        $this->app->singleton(Breadcrumb::class, function () {
            return new BreadcrumbFactory();
        });

        $this->app->singleton(Field::class, function ($app) {
            return new FieldFactory();
        });

        $this->app->singleton(
            RouteResource::class,
            fn($app) => new RouteResourceRepository($app->make('router'))
        );

        $this->app->singleton(
            Menu::class,
            fn($app) => new MenuRepository(
                $app[GlobalData::class],
                $app[Hook::class]
            )
        );

        $this->app->singleton(
            Chart::class,
            fn($app) => new ChartRepository()
        );

        $this->app->singleton(
            Widget::class,
            fn($app) => new WidgetRepository()
        );

        $this->app->singleton(
            Sidebar::class,
            fn($app) => new SidebarRepository()
        );

        $this->app->singleton(
            PageTemplate::class,
            fn($app) => new PageTemplateRepository()
        );

        $this->app->singleton(
            PageBlock::class,
            fn($app) => new PageBlockRepository()
        );

        $this->app->singleton(
            MenuBox::class,
            fn($app) => new MenuBoxRepository($app[GlobalData::class])
        );

        $this->app->singleton(
            NavMenu::class,
            fn($app) => new NavMenuRepository()
        );

        $this->app->singleton(
            Thumbnail::class,
            function ($app) {
                return new ThumbnailRepository();
            }
        );

        $this->app->singleton(
            Sitemap::class,
            fn($app) => new SitemapRepository()
        );
    }

    protected function customServices(): void
    {
        Validator::extend(
            'recaptcha',
            '\Juzaweb\Modules\Admin\Rules\ReCaptchaValidator@validate'
        );

        Validator::extend(
            'domain',
            '\Juzaweb\Modules\Admin\Rules\DomainValidator@validate'
        );

        Validator::extend(
            'cloudflare',
            '\Juzaweb\Modules\Admin\Rules\CloudflareValidator@validate'
        );

        Validator::extend(
            'website_subdomain',
            '\Juzaweb\Modules\Admin\Rules\WebsiteSubdomainValidate@validate'
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

        Blueprint::macro('creator', function () {
            /** @var Blueprint $this */
            $this->uuid('created_by')->nullable();
            $this->string('created_type', 190)->nullable();

            $this->index(['created_by', 'created_type']);
        });
    }

    protected function registerComponents(): void
    {
        Blade::component(
            'js-var',
            JsVar::class
        );

        Blade::component(
            'theme-js-var',
            ThemeJsVar::class
        );

        Blade::component(
            'seo-meta',
            SeoMeta::class
        );

        Blade::component(
            'card',
            Card::class
        );

        Blade::component(
            'repeater',
            Repeater::class
        );

        Blade::component(
            'form',
            Form::class
        );

        Blade::component(
            'language-card',
            LanguageCard::class
        );

        Blade::component(
            'theme-init',
            ThemeInit::class
        );

        Blade::component(
            'cookie-consent',
            CookieConsent::class
        );
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'core');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');
    }

    /**
     * Register views.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $viewPath = resource_path('views/modules/core');

        $sourcePath = __DIR__ . '/../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', 'core-module-views']);

        $this->loadViewsFrom($sourcePath, 'core');
    }
}
