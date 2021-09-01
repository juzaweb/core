<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/25/2021
 * Time: 10:05 PM
 */

use Illuminate\Support\Facades\Auth;
use Juzaweb\Core\Helpers\Breadcrumb;
use Juzaweb\Core\Models\Config;
use Juzaweb\Theme\Models\Menu;
use Juzaweb\Core\Models\User;
use Juzaweb\Theme\Models\Page;
use Juzaweb\Theme\Models\ThemeConfig;
use Illuminate\Support\Str;
use Juzaweb\Core\Facades\Hook;
use Illuminate\Support\Facades\Route;

define('JW_DATE', 1);
define('JW_DATE_TIME', 2);

if (!function_exists('get_client_ip')) {
    /**
     * Get client ip
     *
     * @return string
     * */
    function get_client_ip()
    {
        // Check Cloudflare support
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            return $_SERVER["HTTP_CF_CONNECTING_IP"];
        }

        // Get ip from server
        return request()->ip();
    }
}

if (!function_exists('get_config')) {
    /**
     * Get DB config
     *
     * @param string $key
     * @param mixed $default
     * @return string|array
     * */
    function get_config($key, $default = null)
    {
        return Config::getConfig($key, $default);
    }
}

if (!function_exists('get_configs')) {
    /**
     * Get multi DB configs
     *
     * @param array $keys
     * @param mixed $default
     * @return array
     */
    function get_configs($keys, $default = null)
    {
        $data = [];
        foreach ($keys as $key) {
            $data[$key] = get_config($key, $default);
        }

        return $data;
    }
}

if (!function_exists('set_config')) {
    /**
     * Set DB config
     *
     * @param string $key
     * @param string|array $value
     * @return \Juzaweb\Core\Models\Config
     */
    function set_config($key, $value)
    {
        return Config::setConfig($key, $value);
    }
}

if (!function_exists('generate_token')) {
    /**
     * Generate static by token
     *
     * @param string $string
     * @return string
     */
    function generate_token($string) {
        $month = date('Y-m');
        $ip = get_client_ip();
        $key = 'ADAsd$#&%^23vx' . config('app.key');
        return md5($key . $month . $key) . md5($key . $ip . $string);
    }
}

if (!function_exists('check_token')) {
    /**
     * Check static token
     *
     * @param string $token
     * @param string $string
     * @return bool
     */
    function check_token($token, $string) {
        if (generate_token($string) == $token) {
            return true;
        }

        return false;
    }
}

if (!function_exists('sub_words')) {
    function sub_words($string, int $words = 20) {
        return Str::words($string, $words);
    }
}

if (!function_exists('is_url')) {
    /**
     * Return true if string is a url
     *
     * @param string $url
     * @return bool
     */
    function is_url($url) {
        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return false;
        }

        return true;
    }
}

function menu_info($id) {
    return Menu::where('id', '=', $id)
        ->first(['id', 'name']);
}

function theme_setting($code) {
    $config = ThemeConfig::where('code', '=', $code)
        ->first(['content']);
    
    if ($config) {
        return json_decode($config->content);
    }
    
    return false;
}

function menu_setting($menu_id) {
    try {
        $menu = Menu::find($menu_id);
        if ($menu) {
            return json_decode($menu->content);
        }
    }
    catch (Exception $exception) {
        \Log::error($exception->getMessage());
    }
    return [];
}

function count_unread_notifications() {
    $user = Auth::user();
    if (method_exists($user, 'unreadNotifications')) {
        return $user->unreadNotifications()->count(['id']);
    }

    return 0;
}

function core_path($path = null) {
    if ($path) {
        return __DIR__ . '/../' . $path;
    }

    return __DIR__ . '/../';
}

function user_avatar($user = null) {
    if ($user) {
        if (!is_a($user, User::class)) {
            $user = User::find($user);
        }

        return $user->getAvatar();
    }

    if (Auth::check()) {
        /**
         * @var User $user
         */
        $user = Auth::user();
        return $user->getAvatar();
    }

    return asset('vendor/juzaweb/styles/images/thumb-default.png');
}

if (!function_exists('breadcrumb')) {
    function breadcrumb($name, $addItems = [])
    {
        $items = apply_filters($name . '_breadcrumb', []);

        if ($addItems) {
            foreach ($addItems as $addItem) {
                $items[] = $addItem;
            }
        }

        return Breadcrumb::render($name, $items);
    }
}

function combine_pivot($entities, $pivots = [])
{
    // Set array
    $pivotArray = [];
    // Loop through all pivot attributes
    foreach ($pivots as $pivot => $value) {
        // Combine them to pivot array
        $pivotArray += [$pivot => $value];
    }
    // Get the total of arrays we need to fill
    $total = count($entities);
    // Make filler array
    $filler = array_fill(0, $total, $pivotArray);
    // Combine and return filler pivot array with data
    return array_combine($entities, $filler);
}

if (!function_exists('path_url')) {
    function path_url(string $url)
    {
        if (!is_url($url)) {
            return $url;
        }

        return parse_url($url)['path'];
    }
}

if (!function_exists('upload_url')) {
    /**
     * Get file upload url in public storage
     *
     * @param string $path
     * @param string $default Default path if file not exists
     * @return string
     */
    function upload_url($path, $default = null)
    {
        if (is_url($path)) {
            return $path;
        }

        $storage = Storage::disk('public');
        if ($storage->exists($path)) {
            return $storage->url($path);
        }

        if ($default) {
            return $default;
        }

        return asset('vendor/juzaweb/styles/images/thumb-default.png');
    }
}

if (!function_exists('random_string')) {
    function random_string(int $length = 16)
    {
        return Str::random($length);
    }
}

if (!function_exists('is_json')) {
    /**
     * Rerutn true if string is a json
     *
     * @param string $string
     * @return bool
     */
    function is_json($string) {
        try {
            json_decode($string);
            return json_last_error() === JSON_ERROR_NONE;
        } catch (\Throwable $e) {
            return false;
        }
    }
}

if (!function_exists('do_action')) {
    /**
     * JUZAWEB CMS: Do action hook
     *
     * @param string $tag
     * @param mixed ...$args Additional parameters to pass to the callback functions.
     * @return void
     * */
    function do_action($tag, ...$args) {
        Hook::action($tag, ...$args);
    }
}

if (!function_exists('add_action')) {
    /**
     * JUZAWEB CMS: Add action to hook
     *
     * @param string $tag The name of the filter to hook the $function_to_add callback to.
     * @param callable $callback The callback to be run when the filter is applied.
     * @param int $priority Optional. Used to specify the order in which the functions
     *                                  associated with a particular action are executed.
     *                                  Lower numbers correspond with earlier execution,
     *                                  and functions with the same priority are executed
     *                                  in the order in which they were added to the action. Default 20.
     * @param int $arguments Optional. The number of arguments the function accepts. Default 1.
     * @return void
     */
    function add_action($tag, $callback, $priority = 20, $arguments = 1) {
        Hook::addAction($tag, $callback, $priority, $arguments);
    }
}

if (!function_exists('apply_filters')) {
    /**
     * JUZAWEB CMS: Apply filters to value
     *
     * @param string $tag The name of the filter hook.
     * @param mixed  $value The value to filter.
     * @param mixed  ...$args Additional parameters to pass to the callback functions.
     * @return mixed The filtered value after all hooked functions are applied to it.
     */
    function apply_filters($tag, $value, ...$args) {
        return Hook::filter($tag, $value, ...$args);
    }
}

if (!function_exists('add_filters')) {
    /**
     * @param string $tag The name of the filter to hook the $function_to_add callback to.
     * @param callable $callback The callback to be run when the filter is applied.
     * @param int $priority Optional. Used to specify the order in which the functions
     *                                  associated with a particular action are executed.
     *                                  Lower numbers correspond with earlier execution,
     *                                  and functions with the same priority are executed
     *                                  in the order in which they were added to the action. Default 20.
     * @param int $arguments   Optional. The number of arguments the function accepts. Default 1.
     * @return void
     */
    function add_filters($tag, $callback, $priority = 20, $arguments = 1) {
        Hook::addFilter($tag, $callback, $priority, $arguments);
    }
}

if (! function_exists('is_active_route')) {
    /**
     * Set the active class to the current opened menu.
     *
     * @param  string|array $route
     * @param  string       $className
     * @return string
     */
    function is_active_route($route, $className = 'active')
    {
        if (is_array($route)) {
            return in_array(Route::currentRouteName(), $route) ? $className : '';
        }

        if (Route::currentRouteName() == $route) {
            return $className;
        }

        if (strpos(URL::current(), $route)) {
            return $className;
        }

        return false;
    }
}

if (!function_exists('jw_date_format')) {
    /**
     * Format date to global format cms
     *
     * @param string $date
     * @param int $format // JW_DATE || JW_DATE_TIME
     * @return string
     */
    function jw_date_format($date, $format = JW_DATE_TIME)
    {
        $dateFormat = get_config('date_format', 'F j, Y');
        switch ($format) {
            case JW_DATE:
                return date($dateFormat, $date);
        }

        $timeFormat = get_config('time_format', 'g:i a');

        return date($dateFormat . ' ' . $timeFormat, $date);
    }
}

if (!function_exists('jw_current_user')) {
    /**
     * Get current login user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|User|null
     */
    function jw_current_user()
    {
        return Auth::user();
    }
}

if (!function_exists('jw_get_page')) {
    function jw_get_page($id)
    {
        return Page::find($id);
    }
}
