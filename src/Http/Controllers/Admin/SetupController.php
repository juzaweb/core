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

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Models\Menus\Menu;
use Juzaweb\Modules\Core\Models\Menus\MenuItem;
use Juzaweb\Modules\Core\Models\Pages\Page;
use Juzaweb\Modules\Core\Translations\Models\Language;

class SetupController extends AdminController
{
    /**
     * Display the setup page
     */
    public function index()
    {
        $title = __('core::translation.website_setup');

        return view(
            'core::admin.setup.index',
            compact('title')
        );
    }

    /**
     * Handle the setup process via AJAX
     */
    public function setup(Request $request): JsonResponse
    {
        try {
            DB::transaction(
                function () use ($request) {
                    // Create default language (English)
                    Language::firstOrCreate([
                        'code' => 'en',
                    ], [
                        'name' => 'English',
                    ]);

                    // Create home page with English translation
                    $homePage = Page::updateOrCreate([
                        'template' => 'home',
                    ], [
                        'status' => PageStatus::PUBLISHED,
                        'en' => [
                            'title' => __('core::translation.home'),
                            'locale' => 'en',
                        ],
                    ]);

                    $privacyPolicy = Page::whereTranslation('slug', 'privacy-policy')->first();
                    $termsOfService = Page::whereTranslation('slug', 'terms-of-service')->first();
                    if (! $privacyPolicy) {
                        $privacyPolicy = Page::create([
                            'status' => PageStatus::PUBLISHED,
                            'en' => [
                                'title' => __('core::translation.privacy_policy'),
                                'content' => view('core::frontend.defaults.privacy-policy')->render(),
                                'locale' => 'en',
                            ],
                        ]);
                    }

                    if (! $termsOfService) {
                        $termsOfService = Page::create([
                            'status' => PageStatus::PUBLISHED,
                            'en' => [
                                'title' => __('core::translation.terms_of_service'),
                                'content' => view('core::frontend.defaults.terms')->render(),
                                'locale' => 'en',
                            ],
                        ]);
                    }

                    // Create main menu
                    $mainMenu = Menu::firstOrCreate([
                        'name' => 'Main',
                    ]);

                    // Add home page to main menu
                    MenuItem::firstOrCreate([
                        'menu_id' => $mainMenu->id,
                    ], [
                        'menuable_type' => Page::class,
                        'menuable_id' => $homePage->id,
                        'box_key' => 'pages',
                        'display_order' => 1,
                        'en' => [
                            'label' => 'Home',
                            'locale' => 'en',
                        ],
                    ]);

                    if (!setting('title')) {
                        setting()?->set('title', 'Juzaweb');
                    }

                    if (!setting('description')) {
                        setting()?->set('description', 'Just another Juzaweb site');
                    }
                    if (!setting('language')) {
                        setting()?->set('language', 'en');
                    }
                    if (!setting('theme')) {
                        setting()?->set('theme', $website->theme ?? 'itech');
                    }

                    if (!setting('sitename')) {
                        $host = $request->getHost();
                        $siteName = generate_site_name_from_host($host);
                        setting()?->set('sitename', $siteName);
                    }

                    theme_setting()?->set('home_page', $homePage->id);
                    theme_setting()?->set('nav_location', ['main' => $mainMenu->id]);
                }
            );

            DB::transaction(
                function () {
                    $this->runThemeSeeder();
                }
            );

            return $this->success([
                'message' => __('core::translation.website_setup_completed_successfully'),
                'redirect' => admin_url(),
                'status' => true,
            ]);
        } catch (\Exception $e) {
            report($e);
            return $this->error(__('core::translation.setup_failed_error', ['error' => $e->getMessage()]));
        }
    }

    protected function runThemeSeeder(): void
    {
        $theme = Theme::current()->studlyName();
        $seederClass = "Juzaweb\\Themes\\{$theme}\\Database\\Seeders\\DatabaseSeeder";

        if (class_exists($seederClass)) {
            app($seederClass)->run();
        }
    }
}
