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

use Juzaweb\Modules\Blog\Models\Category;
use Juzaweb\Modules\Core\Contracts\Sitemap;
use Juzaweb\Modules\Core\Facades\Chart;
use Juzaweb\Modules\Core\Facades\Menu;
use Juzaweb\Modules\Core\Facades\MenuBox;
use Juzaweb\Modules\Core\Facades\PageBlock;
use Juzaweb\Modules\Core\Facades\Setting;
use Juzaweb\Modules\Core\Facades\Widget;
use Juzaweb\Modules\Core\Models\Pages\Page;
use Juzaweb\Modules\Core\Models\Pages\PageTranslation;
use Juzaweb\Modules\Core\Support\Dashboard\UsersChart;
use Juzaweb\Modules\Core\Support\Dashboard\SessionDurationChart;
use Juzaweb\Modules\Core\Support\Dashboard\SessionsByDeviceChart;
use Juzaweb\Modules\Core\Support\Dashboard\TopPagesChart;
use Juzaweb\Modules\Core\Support\Dashboard\TrafficSourcesChart;
use Juzaweb\Modules\Core\Support\Dashboard\UsersByCountryChart;

abstract class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerSettings();
        $this->registerCharts();
        $this->registerGlobalPageBlocks();
        $this->registerGlobalWidgets();

        $this->app[Sitemap::class]->register('pages', PageTranslation::class);

        $this->registerMenus();

        $this->registerMenuBoxs();
    }

    public function register(): void
    {
        //
    }

    protected function registerMenus(): void
    {
        Menu::make('dashboard', function () {
            return [
                'title' => __('core::translation.dashboard'),
                'icon' => 'fas fa-tachometer-alt',
                'permission' => ['dashboard.index'],
            ];
        });

        Menu::make('media', function () {
            return [
                'title' => __('core::translation.media'),
                'icon' => 'fas fa-photo-video',
            ];
        });

        Menu::make('pages', function () {
            return [
                'title' => __('core::translation.pages'),
                'icon' => 'fas fa-layer-group',
            ];
        });

        Menu::make('appearance', function () {
            return [
                'title' => __('core::translation.appearance'),
                'icon' => 'fas fa-paint-roller',
                'priority' => 80,
            ];
        });

        Menu::make('themes', function () {
            return [
                'title' => __('core::translation.themes'),
                'parent' => 'appearance',
            ];
        });

        Menu::make('widgets', function () {
            return [
                'title' => __('core::translation.widgets'),
                'parent' => 'appearance',
            ];
        });

        Menu::make('menus', function () {
            return [
                'title' => __('core::translation.menus'),
                'parent' => 'appearance',
            ];
        });

        Menu::make('modules', function () {
            return [
                'title' => __('core::translation.modules'),
                'icon' => 'fas fa-cubes',
                'priority' => 90,
            ];
        });

        Menu::make('settings', function () {
            return [
                'title' => __('core::translation.settings'),
                'icon' => 'fas fa-cogs',
                'priority' => 99,
            ];
        });

        Menu::make('general', function () {
            return [
                'title' => __('core::translation.general'),
                'url' => 'settings/general',
                'parent' => 'settings',
            ];
        });

        Menu::make('social-login', function () {
            return [
                'title' => __('core::translation.social_login'),
                'url' => 'settings/social-login',
                'parent' => 'settings',
            ];
        });

        Menu::make('email', function () {
            return [
                'title' => __('core::translation.email'),
                'url' => 'settings/email',
                'parent' => 'settings',
            ];
        });

        Menu::make('users-roles', function () {
            return [
                'title' => __('Users and Roles'),
                'priority' => 90,
            ];
        });

        Menu::make('users', function () {
            return [
                'title' => __('core::translation.users'),
                'parent' => 'users-roles',
            ];
        });

        Menu::make('roles', function () {
            return [
                'title' => __('core::translation.roles'),
                'parent' => 'users-roles',
            ];
        });

        Menu::make('languages', function () {
            return [
                'title' => __('core::translation.languages'),
                'parent' => 'settings',
            ];
        });
    }

    protected function registerMenuBoxs(): void
    {
        MenuBox::make('pages', Page::class, function () {
            return [
                'label' => __('core::translation.pages'),
                'icon' => 'fas fa-layer-group',
                'priority' => 1,
                'field' => 'title',
            ];
        });

        MenuBox::make('post-categories', Category::class, function () {
            return [
                'label' => __('core::translation.categories'),
                'icon' => 'fas fa-newspaper',
                'priority' => 1,
                'field' => 'name',
            ];
        });
    }

    protected function registerSettings(): void
    {
        $this->booted(
            function () {
                Setting::make('title')->default(config('app.name'));

                Setting::make('description');
                Setting::make('sitename');

                Setting::make('logo');
                Setting::make('favicon');
                Setting::make('banner');

                Setting::make('user_registration')->default(true);

                Setting::make('user_verification')->default(false);

                Setting::make('multiple_language')->default('none');
                Setting::make('language')->default('en');

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

                Setting::make('mail_host')->rules(['nullable', 'string']);
                Setting::make('mail_port')->rules(['nullable', 'integer', 'min:1', 'max:65535']);
                Setting::make('mail_username')->rules(['nullable', 'string']);
                Setting::make('mail_password')->rules(['nullable', 'string']);
                Setting::make('mail_encryption')->rules(['nullable', 'string', 'in:tls,ssl']);
                Setting::make('mail_from_address')->rules(['nullable', 'email']);
                Setting::make('mail_from_name')->rules(['nullable', 'string']);

                // Custom Scripts Settings
                Setting::make('custom_header_script')->rules(['nullable', 'string']);
                Setting::make('custom_footer_script')->rules(['nullable', 'string']);

                Setting::make('google_analytics_id')->rules(['nullable', 'string']);

                // Cookie Consent Settings
                Setting::make('cookie_consent_enabled')->default(false);
                Setting::make('cookie_consent_message')->rules(['nullable', 'string']);
            }
        );
    }

    protected function registerCharts(): void
    {
        Chart::chart('users', UsersChart::class);
        Chart::chart('users-by-country', UsersByCountryChart::class);
        Chart::chart('sessions-by-device', SessionsByDeviceChart::class);
        Chart::chart('top-pages', TopPagesChart::class);
        Chart::chart('session-duration', SessionDurationChart::class);
        Chart::chart('traffic-sources', TrafficSourcesChart::class);
    }

    protected function registerGlobalPageBlocks(): void
    {
        PageBlock::make(
            'html',
            function () {
                return [
                    'label' => __('core::translation.html_block'),
                    'form' => 'core::global.blocks.html.form',
                    'view' => 'core::global.blocks.html.view',
                ];
            }
        );
    }

    protected function registerGlobalWidgets(): void
    {
        Widget::make(
            'html',
            function () {
                return [
                    'label' => __('core::translation.html_widget'),
                    'description' => __('core::translation.display_custom_html_content'),
                    'form' => 'core::global.widgets.html.form',
                    'view' => 'core::global.widgets.html.show',
                ];
            }
        );
    }
}
