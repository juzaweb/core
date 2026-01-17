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
use Juzaweb\Modules\Admin\Enums\UserStatus;
use Juzaweb\Modules\Admin\Models\Users\Member;
use Juzaweb\Modules\Admin\Networks\Facades\Network;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Models\Language;
use Juzaweb\Modules\Core\Models\Menus\Menu;
use Juzaweb\Modules\Core\Models\Menus\MenuItem;
use Juzaweb\Modules\Core\Models\Pages\Page;

class SetupController extends AdminController
{
    /**
     * Display the setup page
     */
    public function index()
    {
        $website = Network::website();

        if ($website && $website->setup) {
            return redirect()->route('admin.dashboard', [$website->id]);
        }

        $title = __('admin::translation.website_setup');

        return view(
            'admin::admin.setup.index',
            compact('website', 'title')
        );
    }

    /**
     * Handle the setup process via AJAX
     */
    public function setup(Request $request): JsonResponse
    {
        $website = Network::website();

        if (! $website) {
            return $this->error(__('admin::translation.website_not_found'));
        }

        if ($website->setup) {
            return $this->error(__('admin::translation.website_is_already_set_up'));
        }

        $user = $request->user();

        try {
            DB::transaction(
                function () use ($website, $user) {
                    Member::firstOrCreate([
                        'email' => $user->email,
                    ], [
                        'name' => $user->name,
                        'user_id' => $user->id,
                        'password' => $user->password,
                        'status' => UserStatus::ACTIVE,
                    ]);

                    // Create default language (English)
                    Language::firstOrCreate([
                        'code' => 'en',
                    ], [
                        'name' => 'English',
                    ]);

                    if ($website->language && $website->language != 'en') {
                        Language::firstOrCreate([
                            'code' => $website->language,
                        ], [
                            'name' => config("locales.{$website->language}.name", 'Unknown'),
                        ]);
                    } else {
                        $website->update(['language' => 'en']);
                    }

                    // Create home page with English translation
                    $homePage = Page::updateOrCreate([
                        'template' => 'home',
                    ], [
                        'status' => PageStatus::PUBLISHED,
                        'en' => [
                            'title' => __('admin::translation.home'),
                            'locale' => 'en',
                        ],
                    ]);

                    $privacyPolicy = Page::whereTranslation('slug', 'privacy-policy')->first();
                    $termsOfService = Page::whereTranslation('slug', 'terms-of-service')->first();
                    if (! $privacyPolicy) {
                        $privacyPolicy = Page::create([
                            'status' => PageStatus::PUBLISHED,
                            $website->language => [
                                'title' => __('admin::translation.privacy_policy'),
                                'content' => view('admin::frontend.defaults.privacy-policy')->render(),
                                'locale' => $website->language,
                            ],
                        ]);
                    }

                    if (! $termsOfService) {
                        $termsOfService = Page::create([
                            'status' => PageStatus::PUBLISHED,
                            $website->language => [
                                'title' => __('admin::translation.terms_of_service'),
                                'content' => view('admin::frontend.defaults.terms')->render(),
                                'locale' => $website->language,
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
                        setting()?->set('title', $website->title);
                    }

                    if (!setting('description')) {
                        setting()?->set('description', $website->description);
                    }
                    if (!setting('language')) {
                        setting()?->set('language', $website->language ?? 'en');
                    }
                    if (!setting('theme')) {
                        setting()?->set('theme', $website->theme ?? 'itech');
                    }
                    if (!setting('sitename')) {
                        $host = parse_url($website->url, PHP_URL_HOST);
                        $siteName = generate_site_name_from_host($host);
                        setting()?->set('sitename', $siteName);
                    }

                    theme_setting()?->set('home_page', $homePage->id);
                    theme_setting()?->set('nav_location', ['main' => $mainMenu->id]);

                    // Mark website as set up
                    $website->setup = true;
                    $website->save();
                }
            );

            DB::transaction(
                function () {
                    $this->runThemeSeeder();
                }
            );

            return $this->success([
                'message' => __('admin::translation.website_setup_completed_successfully'),
                'redirect' => admin_url(),
                'status' => true,
            ]);
        } catch (\Exception $e) {
            report($e);
            return $this->error(__('admin::translation.setup_failed_error', ['error' => $e->getMessage()]));
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
