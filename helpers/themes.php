<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\ThemeSetting;
use Juzaweb\Modules\Core\Models\Menus\Menu;
use Juzaweb\Modules\Core\Models\Pages\Page;
use Juzaweb\Modules\Core\Models\ThemeSidebar;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable as TranslatableContract;

if (!function_exists('setting')) {
    /**
     * Get or set a setting value
     *
     * @param  string|null  $key  The setting key
     * @param  string|array|null  $default  The default value if the setting doesn't exist
     * @return string|array|null|ThemeSetting A setting value or the Setting instance if no key is provided
     */
    function theme_setting(?string $key = null, string|array|null $default = null): null|string|array|ThemeSetting
    {
        if (func_num_args() > 0) {
            // Get a setting value
            return app(ThemeSetting::class)->get($key, $default);
        }

        // Return the Setting instance if no key is provided
        return app(ThemeSetting::class);
    }
}

if (!function_exists('logo_url')) {
    /**
     * Generate a URL for an asset in the current theme
     *
     * @param  string  $path  The asset path relative to the theme's assets directory
     * @return string The generated URL for the asset
     */
    function logo_url(?string $default = null): ?string
    {
        return ($logo = setting('logo')) ? upload_url($logo) : $default;
    }
}

if (!function_exists('widgets_sidebar')) {
    /**
     * Retrieve widgets for a specific sidebar
     *
     * @param  string  $key  The sidebar key
     * @return Collection An array of widgets associated with the specified sidebar
     */
    function widgets_sidebar(string $key): Collection
    {
        return collect(theme_setting("sidebar_{$key}", []));
    }
}

if (!function_exists('active_theme')) {
    /**
     * Get the currently active theme
     *
     * @return string The name of the active theme
     */
    function active_theme(): string
    {
        return Theme::current()->name();
    }
}

function nav_location(string $location): ?Menu
{
    $locations = theme_setting('nav_location', []);

    if (!isset($locations[$location])) {
        return null;
    }

    $menu = Menu::whereFrontend()
        ->where('id', $locations[$location])
        ->first();

    loadMenuItems($menu->items);

    return $menu;
}

function loadMenuItems($items)
{
    $locales = array_unique([app()->getLocale(), config('translatable.fallback_locale')]);
    foreach ($items as $item) {
        // Chỉ tải bản dịch cho các đối tượng có thể dịch (Translatable)
        if ($item->menuable instanceof TranslatableContract) {
            // Đặt ngôn ngữ mặc định (current locale) cho đối tượng
            $item->menuable->setDefaultLocale(app()->getLocale());

            // Tải bản dịch thiếu (missing translations) cho các ngôn ngữ đã xác định, sử dụng cache
            $item->menuable->loadMissing([
                'translations' => fn($q) => $q->cacheFor(3600)->whereIn('locale', $locales),
            ]);
        }

        // Luôn gọi đệ quy hàm loadMenuItems cho các mục con,
        // bất kể mục cha có phải là TranslatableContract hay không.
        if ($item->children->isNotEmpty()) {
            loadMenuItems($item->children);
        }
    }
}

function sidebars(string $name): EloquentCollection
{
    return ThemeSidebar::whereFrontend()
        ->where(['sidebar' => $name])
        ->orderBy('display_order', 'asc')
        ->get();
}

function page_get(null|string|Page $page): ?Page
{
    if (! $page) {
        return null;
    }

    if ($page instanceof Page) {
        return $page;
    }

    return Page::whereFrontend()->find($page);
}

function page_blocks(?string $page): Collection
{
    $page = page_get($page);

    if (! $page) {
        return collect();
    }

    $page->loadMissing(
        ['blocks' => fn($q) => $q->withTranslation()->cacheFor(3600)]
    );

    return $page->blocks->groupBy('container') ?? collect();
}

function dynamic_block(Page|string|null $page, string $container): Factory|\Illuminate\View\View|null
{
    if ($page === null) {
        return null;
    }

    $blocks = page_blocks($page)->get($container)?->sortBy('display_order');

    return view(
        'core::frontend.dynamic-block',
        compact('blocks')
    );
}

function ads_position(string $position): ?string
{
    return app(\Juzaweb\Modules\AdsManagement\Ads::class)->getBanner($position)?->getBody();
}

function dynamic_sidebar(string $name): Factory|\Illuminate\Contracts\View\View
{
    $sidebars = sidebars($name);

    return view(
        'core::frontend.dynamic-sidebar',
        compact('sidebars')
    );
}

function page_view_name(Page $page, string $namespace): string
{
    if ($page->template && view()->exists("{$namespace}::templates.{$page->template}")) {
        return "{$namespace}::templates.{$page->template}";
    }

    return "{$namespace}::page.show";
}

function social_login_providers(): Collection
{
    return collect(config('app.social_login.providers', []))
        ->map(fn($item, $key) => title_from_key($key))
        ->filter(fn ($item, $key) => setting("{$key}_login", false));
}
