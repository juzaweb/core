<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Providers;

use Illuminate\Support\Facades\Blade;
use Juzaweb\Core\Facades\Dashboard;
use Juzaweb\Core\Facades\Menu;
use Juzaweb\Core\Facades\Setting;
use Juzaweb\Core\Support\Dashboard\UserBox;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerSettings();
        $this->registerMenus();
        $this->registerDashboardBoxes();
        $this->registerComponents();
    }

    protected function registerMenus(): void
    {
        $this->booted(
            function () {
                Menu::make('dashboard', __('Dashboard'))
                    ->icon('fas fa-tachometer-alt');

                Menu::make('pages', __('Pages'))
                    ->icon('fas fa-file');

                Menu::make('settings', __('Settings'))
                    ->icon('fas fa-cogs')
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
                Setting::make('title')->default(config('app.name'));

                Setting::make('description');
                Setting::make('sitename');

                Setting::make('logo');
                Setting::make('favicon');
                Setting::make('banner');

                Setting::make('recaptcha2_site_key');
                Setting::make('recaptcha2_secret_key');

                Setting::make('user_registration')->default(true);

                Setting::make('user_verification')->default(false);

                Setting::make('multiple_language')->default('none');

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

    protected function registerDashboardBoxes(): void
    {
        Dashboard::box('users', new UserBox());

        Dashboard::chart('users', new \Juzaweb\Core\Support\Dashboard\UserChart());
    }

    protected function registerComponents(): void
    {
        Blade::component(
            'js-var',
            \Juzaweb\Core\View\Components\JsVar::class
        );

        Blade::component(
            'seo-meta',
            \Juzaweb\Core\View\Components\SeoMeta::class
        );

        Blade::component(
            'card',
            \Juzaweb\Core\View\Components\Card::class
        );

        Blade::component(
            'language-card',
            \Juzaweb\Core\View\Components\LanguageCard::class
        );
    }
}
