<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Juzaweb\Core\Contracts\Setting;
use Juzaweb\Core\Facades\Theme;
use Juzaweb\Core\Models\User;
use Juzaweb\Translations\Models\Language;

require __DIR__ .'/modules.php';

if (! function_exists('client_ip')) {
    /**
     * Get client ip
     *
     * @return string
     * */
    function client_ip(): ?string
    {
        // Check Cloudflare support
        return $_SERVER["HTTP_CF_CONNECTING_IP"] ?? request()?->ip();
    }
}

if (! function_exists('is_json')) {
    /**
     * Rerutn true if string is a json
     *
     * @param string $string
     * @return bool
     */
    function is_json(mixed $string): bool
    {
        try {
            json_decode($string);

            return json_last_error() === JSON_ERROR_NONE;
        } catch (Throwable $e) {
            return false;
        }
    }
}

if (! function_exists('setting')) {
    /**
     * Get or set a setting value
     *
     * @param string|null $key The setting key
     * @param string|array|null $default The default value if the setting doesn't exist
     * @return string|array|null|Setting A setting value or the Setting instance if no key is provided
     */
    function setting(?string $key = null, string|array|null $default = null): null|string|array|Setting
    {
        if (func_num_args() > 0) {
            // Get a setting value
            return app(Setting::class)->get($key, $default);
        }

        // Return the Setting instance if no key is provided
        return app(Setting::class);
    }
}

if (! function_exists('cache_prefix')) {
    function cache_prefix(string $key): string
    {
        return "larabiz_{$key}";
    }
}

if (! function_exists('title_from_key')) {
    /**
     * Generate a title from the given key.
     *
     * @param string $key The key to generate a title from.
     * @return string The generated title.
     */
    function title_from_key(string $key): string
    {
        // Split the key by '.' and only keep the key after the first dot if it exists
        $keys = explode('.', $key);
        if (count($keys) > 1) {
            $key = $keys[1];
        }

        // Replace underscores, dashes, forward slashes, and backslashes with spaces
        // and then convert the string to title case
        return Str::title(Str::replace(['_', '-', '/', '\\'], ' ', $key));
    }
}

if (! function_exists('key_from_string')) {
    /**
     * Convert a string into a key-friendly format by replacing slashes with spaces,
     * and then converting it into a slug with underscores as separators.
     *
     * @param string $str The input string to be converted.
     * @return string The key-friendly formatted string.
     */
    function key_from_string(string $str): string
    {
        return Str::slug(Str::replace('/', ' ', $str), '_');
    }
}

if (! function_exists('home_url')) {
    /**
     * Generate a home URL from the given URI.
     *
     * @param string|null $uri The URI to generate a URL for.
     *
     * @return string The generated URL.
     */
    function home_url(?string $uri = null): string
    {
        return route('home'). ($uri ? '/' . ltrim($uri, '/') : '');
    }
}

if (! function_exists('admin_url')) {
    /**
     * Generate an admin URL from the given URI.
     *
     * @param string $uri the URI to generate a URL for
     *
     * @return string the generated URL
     */
    function admin_url(?string $uri = null): string
    {
        return url(rtrim(
            '/' . config('core.admin_prefix') . '/'
            . ltrim($uri, '/'),
            '/'
        ));
    }
}

if (! function_exists('format_size_units')) {
    /**
     * Convert a size in bytes to a human-readable string representation using appropriate units.
     *
     * @param int $bytes The size in bytes to be converted.
     * @param  int  $decimals The number of decimal places to use in the formatted output. Default is 2.
     * @return string A human-readable string representing the size in bytes using appropriate units (GB, MB, KB, bytes).
     */
    function format_size_units($bytes, int $decimals = 2): string
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, $decimals) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, $decimals) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, $decimals) . ' KB';
        } elseif ($bytes > 1) {
            $bytes .= ' bytes';
        } elseif ($bytes == 1) {
            $bytes .= ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}

if (! function_exists('is_super_admin')) {
    /**
     * Check if user is super admin
     *
     * @param User|null $user
     * @return bool
     */
    function is_super_admin(?User $user = null): bool
    {
        if ($user === null) {
            $user = auth()->user();
        }

        if ($user === null) {
            return false;
        }

        return $user->isSuperAdmin();
    }
}

if (! function_exists('languages')) {
    /**
     * Get all languages.
     *
     * @return \Illuminate\Support\Collection
     */
    function languages(): Collection
    {
        return Language::languages();
    }
}

if (! function_exists('used_recaptcha')) {
    function used_recaptcha(): bool
    {
        return config('larabiz.recaptcha.secret') !== null;
    }
}

if (! function_exists('date_range')) {
    /**
     * Returns an array of strings representing the dates in the given range.
     *
     * @param  Carbon  $from
     * @param  Carbon  $to
     * @param  string  $format
     * @return array
     */
    function date_range(Carbon $from, Carbon $to, string $format = 'd/m/Y'): array
    {
        $result = [];
        $dates = CarbonPeriod::create($from, $to);
        foreach ($dates as $date) {
            $result[] = $date->format($format);
        }

        return $result;
    }
}

if (! function_exists('month_range')) {
    /**
     * Returns an array of strings representing the months in the given date range.
     *
     * @param Carbon $from The start date of the range.
     * @param Carbon $to The end date of the range.
     * @param string $format The format for the month strings (default is d/m).
     * @return array An array of strings in d/m format, representing each month in the range.
     */
    function month_range(Carbon $from, Carbon $to, string $format = 'm/Y'): array
    {
        $result = [];

        // Normalize start and end to beginning of month
        $from = $from->copy()->startOfMonth();
        $to = $to->copy()->startOfMonth();

        // Create monthly period
        $period = CarbonPeriod::create($from, '1 month', $to);

        foreach ($period as $date) {
            $result[] = $date->format($format);
        }

        return $result;
    }
}

if (! function_exists('array_to_array_string')) {
    /**
     * Converts an array to a string, similar to var_export.
     * This method is useful for debugging and logging.
     *
     * @param array $array The array to convert.
     * @return string The string representation of the array.
     *
     * Example:
     * array_to_array_string(['foo', 'bar', 3]) // returns "['foo', 'bar', 3]"
     */
    function array_to_array_string(array $array): string
    {
        $string = json_encode($array, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);

        return str_replace(['"', '{', '}', ': '], ["'", '[', ']', ' => '], $string);
    }
}

if (! function_exists('default_language')) {
    function default_language(): string
    {
        return config('app.locale');
    }
}

if (!function_exists('is_url')) {
    /**
     * Return true if string is a url
     *
     * @param string|null $url
     * @return bool
     */
    function is_url(?string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH);
        $encoded_path = array_map('urlencode', explode('/', $path));
        $url = str_replace($path, implode('/', $encoded_path), $url);

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

if (!function_exists('is_admin_page')) {
    /**
     * Determine if the current page is an admin page.
     *
     * @return bool
     */
    function is_admin_page(): bool
    {
        // Check if the current page is an admin page by checking if the
        // current URL matches the prefix defined in the config.
        return request()->is(config('core.admin_prefix') . '*');
    }
}

if (!function_exists('get_error_by_exception')) {
    /**
     * Extracts error information from the given exception.
     *
     * @param  Throwable  $e The exception to extract information from.
     * @return array An associative array containing the error information.
     *               The keys in the array are:
     *               - message: The error message.
     *               - line: The line number where the error occurred.
     *               - file: The file path where the error occurred.
     *               - code: The error code.
     *               - exception: The class name of the exception.
     */
    function get_error_by_exception(Throwable $e): array
    {
        return [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'code' => $e->getCode(),
            'exception' => get_class($e),
        ];
    }
}

if (!function_exists('get_domain_by_url')) {
    /**
     * Extracts the domain from a given URL.
     *
     * @param string $url The URL to extract the domain from.
     * @param bool $noneWWW If true, remove 'www.' from the domain.
     * @return string|bool The extracted domain or false if the URL is invalid.
     */
    function get_domain_by_url(string $url, bool $noneWWW = false): string|bool
    {
        // Check if the URL starts with a valid protocol or is protocol-relative
        if (str_starts_with($url, 'https://') || str_starts_with($url, 'http://') || str_starts_with($url, '//')) {
            // Extract the domain from the URL
            $domain = explode('/', $url)[2];

            // Remove 'www.' prefix if $noneWWW is true
            if ($noneWWW && str_starts_with($domain, 'www.')) {
                $domain = str_replace('www.', '', $domain);
            }

            // Return the domain without any query parameters
            return explode('?', $domain)[0];
        }

        // Return false if the URL does not start with a valid protocol
        return false;
    }
}

if (!function_exists('number_human_format')) {
    /**
     * Formats a given number as a human-readable string.
     *
     * @param int $number The number to format.
     * @return string The formatted number as a string.
     */
    function number_human_format(int $number): string
    {
        // If the number is less than 100k, just use the standard number format
        if ($number < 100000) {
            return number_format($number);
        }

        // If the number is between 100k and 1M, use 'k' as the suffix
        if ($number < 1000000) {
            return number_format($number / 1000, 2) . 'K';
        }

        // If the number is between 1M and 1B, use 'M' as the suffix
        if ($number < 1000000000) {
            return number_format($number / 1000000, 2) . 'M';
        }

        // If the number is between 1B and 1T, use 'B' as the suffix
        if ($number < 1000000000000) {
            return number_format($number / 1000000000, 2) . 'B';
        }

        // If the number is greater than 1T, use 'T' as the suffix
        return number_format($number / 1000000000000, 2) . 'T';
    }
}

if (! function_exists('upload_url')) {
    /**
     * Get the URL of the uploaded file.
     *
     * @param string $path The path to the uploaded file.
     * @return string The URL of the uploaded file.
     */
    function upload_url(?string $path = null): ?string
    {
        if ($path === null) {
            return $path;
        }

        if (is_url($path)) {
            return $path;
        }

        return asset('storage/' . $path);
    }
}

if (! function_exists('map_params')) {
    /**
     * Replace placeholders in a string with values from an associative array.
     *
     * This function searches for placeholders in the format {placeholder} within
     * the provided text and replaces them with corresponding values from the
     * provided associative array. If a placeholder does not exist in the array,
     * it remains unchanged in the text.
     *
     * @param string $text The text containing placeholders to replace.
     * @param array $params An associative array of placeholder names and their replacement values.
     * @return string The text with placeholders replaced by values from the array.
     */
    function map_params(string $text, array $params): string
    {
        return preg_replace_callback(
            '/\{(\w+)\}/',
            function ($matches) use ($params) {
                if (! isset($params[$matches[1]])) {
                    throw new RuntimeException("Param {$matches[1]} not found");
                }

                // Return the replacement value if it exists
                return $params[$matches[1]];
            },
            $text
        );
    }
}

if (! function_exists('theme_path')) {
    /**
     * Get the path to the theme directory.
     *
     * @return string The path to the theme directory.
     */
    function theme_path(?string $path = null): string
    {
        return config('themes.path') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (! function_exists('theme_asset')) {
    /**
     * Get the path to the theme directory.
     *
     * @return string The path to the theme directory.
     */
    function theme_asset(string $path, ?string $theme = null): string
    {
        $theme = $theme ?? Theme::current()->name();

        return asset("themes/{$theme}/" . ($path ? ltrim($path, '/') : ''));
    }
}

if (!function_exists('get_youtube_id')) {
    /**
     * Extracts the YouTube video ID from a given URL.
     *
     * This function uses a regular expression to match the YouTube video ID
     * from various formats of YouTube URLs, including standard and shortened URLs.
     *
     * @param  string  $url The YouTube URL from which to extract the video ID.
     * @return string|null The extracted video ID, or null if no valid ID is found.
     */
    function get_youtube_id(string $url): ?string
    {
        preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $url,
            $match
        );

        if (@$match[1]) {
            return $match[1];
        }

        return null;
    }
}

if (!function_exists('get_vimeo_id')) {
    /**
     * Extracts the Vimeo video ID from a given URL.
     *
     * This function uses a regular expression to match the Vimeo video ID
     * from various formats of Vimeo URLs, including standard and player URLs.
     *
     * @param string $url The Vimeo URL from which to extract the video ID.
     * @return string|null The extracted video ID, or an empty string if no valid ID is found.
     */
    function get_vimeo_id(string $url): ?string
    {
        $regs = [];
        $id = '';
        if (preg_match(
            '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/'
            . '(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)'
            . '(\d+)(?:$|\/|\?)(?:[?]?.*)$%im',
            $url,
            $regs
        )
        ) {
            $id = $regs[3];
        }

        return $id;
    }
}

if (!function_exists('get_google_drive_id')) {
    /**
     * Extracts the Google Drive file ID from a given URL.
     *
     * This function assumes that the Google Drive URL is in the format:
     * https://drive.google.com/file/d/FILE_ID/view?usp=sharing
     * and extracts the FILE_ID part from it.
     *
     * @param string $url The Google Drive URL from which to extract the file ID.
     * @return string The extracted file ID.
     */
    function get_google_drive_id(string $url): string
    {
        return explode('/', $url)[5];
    }
}

if (!function_exists('convert_linux_path')) {
    /**
     * Convert a Windows-style path to a Linux-style path.
     *
     * This function replaces backslashes with forward slashes in the given path.
     *
     * @param string $path The Windows-style path to be converted.
     * @return string The converted Linux-style path.
     */
    function convert_linux_path(string $path): string
    {
        return str_replace(
            '\\',
            '/',
            $path
        );
    }
}

if (!function_exists('remove_zero_width_space_string')) {
    /**
     * Remove zero width space string
     *
     * @param string $string
     * @return string
     */
    function remove_zero_width_space_string(string $string): string
    {
        $string = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $string);
        return preg_replace('/\xc2\xa0/', '', $string);
    }
}

if (!function_exists('seo_string')) {
    /**
     * Generate a SEO-friendly string from the given input.
     *
     * This function strips HTML tags, replaces newlines and tabs with spaces,
     * decodes HTML entities, and truncates the string to a specified length
     * without cutting off in the middle of a word.
     *
     * @param  string  $string The input string to be processed.
     * @param  int  $chars The maximum length of the resulting string (default is 70).
     * @return string The processed SEO-friendly string.
     */
    function seo_string(string $string, int $chars = 70): string
    {
        $string = strip_tags($string);
        $string = str_replace(["\n", "\t"], ' ', $string);
        $string = remove_zero_width_space_string(html_entity_decode($string, ENT_HTML5));
        return sub_char($string, $chars);
    }
}

if (!function_exists('sub_char')) {
    /**
     * Truncate a string to a specified length, ensuring it does not cut off in the middle of a word.
     *
     * @param  string  $str The input string to be truncated.
     * @param  int  $n The maximum length of the truncated string.
     * @param  string  $end The string to append at the end if truncation occurs (default is '...').
     * @return string The truncated string, or the original string if it is shorter than $n.
     */
    function sub_char(string $str, int $n, string $end = '...'): string
    {
        if (strlen($str) < $n) {
            return $str;
        }

        $html = mb_substr($str, 0, $n);
        $html = mb_substr($html, 0, mb_strrpos($html, ' '));
        return $html . $end;
    }
}

if (!function_exists('str_slug')) {
    /**
     * Generate a URL-friendly slug from the given string.
     *
     * This function replaces spaces and special characters with hyphens,
     * converts the string to lowercase, and removes any leading or trailing hyphens.
     *
     * @param  string  $string The input string to be converted to a slug.
     * @return string The generated slug.
     */
    function str_slug(string $string): string
    {
        return Str::slug($string);
    }
}
