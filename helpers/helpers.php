<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 * @license    GNU V2
 */

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Juzaweb\Core\Contracts\Setting;
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
    function setting(?string $key = null, string|array|null $default = null): null|string|array|Setting
    {
        if (func_num_args() > 0) {
            return app(Setting::class)->get($key, $default);
        }

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
    function title_from_key(string $key): string
    {
        $keys = explode('.', $key);
        if (count($keys) > 1) {
            $key = $keys[1];
        }

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
     * @param int $decimals The number of decimal places to use in the formatted output. Default is 2.
     * @return string A human-readable string representing the size in bytes using appropriate units (GB, MB, KB, bytes).
     */
    function format_size_units($bytes, $decimals = 2): string
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
    function date_range(Carbon $from, Carbon $to): array
    {
        $result = [];
        $dates = CarbonPeriod::create($from, $to);

        foreach ($dates as $date) {
            $result[] = $date->format('Y-m-d');
        }

        return $result;
    }
}

if (! function_exists('month_range')) {
    function month_range(Carbon $from, Carbon $to): array
    {
        $result = [];
        $dates = CarbonPeriod::create($from, $to);

        foreach ($dates as $date) {
            $result[] = $date->format('Y-m');
        }

        return $result;
    }
}

if (! function_exists('array_to_array_string')) {
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
    function is_admin_page(): bool
    {
        return request()->is(config('core.admin_prefix') . '*');
    }
}

if (!function_exists('get_error_by_exception')) {
    function get_error_by_exception(\Throwable $e): array
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
    function get_domain_by_url(string $url, bool $noneWWW = false): string|bool
    {
        if (str_starts_with($url, 'https://')
            || str_starts_with($url, 'http://')
            || str_starts_with($url, '//')
        ) {
            $domain = explode('/', $url)[2];
            if ($noneWWW) {
                if (str_starts_with($domain, 'www.')) {
                    $domain = str_replace('www.', '', $domain);
                }
            }

            return explode('?', $domain)[0];
        }

        return false;
    }
}

if (!function_exists('number_human_format')) {
    function number_human_format(int $number): string
    {
        if ($number < 1000000) {
            return number_format($number);
        }

        if ($number < 1000000000) {
            return number_format($number / 1000000, 2) . ' M';
        }

        if ($number < 1000000000000) {
            return number_format($number / 1000000000, 2) . ' B';
        }

        return number_format($number / 1000000000000, 2) . ' T';
    }
}

if (! function_exists('upload_url')) {
    function upload_url(string $path): string
    {
        return asset('storage/' . $path);
    }
}
