<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

if (!function_exists('media_url')) {
    function media_url(string $path, string $disk = 'public'): string
    {
        return Storage::disk($disk)->url($path);
    }
}

if (!function_exists('media')) {
    /**
     * @return \Juzaweb\Modules\Core\FileManager\MediaRepository
     */
    function media(): \Juzaweb\Modules\Core\FileManager\MediaRepository
    {
        return app(\Juzaweb\Modules\Core\FileManager\Contracts\Media::class);
    }
}

if (!function_exists('format_size_units')) {
    /**
     * Convert a size in bytes to a human-readable string representation using appropriate units.
     *
     * @param  int  $bytes  The size in bytes to be converted.
     * @param  int  $decimals  The number of decimal places to use in the formatted output. Default is 2.
     * @return string A human-readable string representing the size in bytes using appropriate units (GB, MB, KB, bytes).
     */
    function format_size_units($bytes, int $decimals = 2): string
    {
        if (! is_numeric($bytes)) {
            return $bytes;
        }

        if ($bytes >= 1099511627776) {
            $bytes = number_format($bytes / 1099511627776, $decimals).' TB';
        } elseif ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, $decimals).' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, $decimals).' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, $decimals).' KB';
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

if (!function_exists('is_url')) {
    /**
     * Return true if string is a url
     *
     * @param  string|null  $url
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

if (!function_exists('get_base_url')) {
    function get_base_url(string $url): string
    {
        $parse = parse_url($url);
        $scheme = isset($parse['scheme']) ? $parse['scheme'].'://' : '';
        $host = $parse['host'] ?? '';
        return "{$scheme}{$host}";
    }
}

/** Build a URL
 *
 * @param array $parts An array that follows the parse_url scheme
 * @return string
 */
function build_url($parts): string
{
    if (empty($parts['user'])) {
        $url = $parts['scheme'] . '://' . $parts['host'];
    } elseif (empty($parts['pass'])) {
        $url = $parts['scheme'] . '://' . $parts['user'] . '@' . $parts['host'];
    } else {
        $url = $parts['scheme'] . '://' . $parts['user'] . ':' . $parts['pass'] . '@' . $parts['host'];
    }

    if (!empty($parts['port'])) {
        $url .= ':' . $parts['port'];
    }

    if (!empty($parts['path'])) {
        $url .= $parts['path'];
    }

    if (!empty($parts['query'])) {
        $url .= '?' . $parts['query'];
    }

    if (!empty($parts['fragment'])) {
        return $url . '#' . $parts['fragment'];
    }

    return $url;
}

/** Convert a relative path in to an absolute path
 *
 * @param string $path
 * @return string
 */
function abs_path(string $path): string
{
    $path_array = explode('/', $path);

    // Solve current and parent folder navigation
    $translated_path_array = array();
    $i = 0;
    foreach ($path_array as $name) {
        if ($name === '..') {
            unset($translated_path_array[--$i]);
        } elseif (!empty($name) && $name !== '.') {
            $translated_path_array[$i++] = $name;
        }
    }

    return '/' . implode('/', $translated_path_array);
}

/** Convert a relative URL in to an absolute URL
 *
 * @param string $url URL or URI
 * @param string $base Absolute URL
 * @return string
 */
function abs_url(string $url, string $base): string
{
    $url_parts = parse_url($url);
    $base_parts = parse_url($base);

    // Handle the path if it is specified
    if (!empty($url_parts['path'])) {
        // Is the path relative
        if ($url_parts['path'][0] !== '/') {
            if (substr($base_parts['path'], -1) === '/') {
                $url_parts['path'] = $base_parts['path'] . $url_parts['path'];
            } else {
                $url_parts['path'] = dirname($base_parts['path']) . '/' . $url_parts['path'];
            }
        }

        // Make path absolute
        $url_parts['path'] = abs_path($url_parts['path']);
    }

    // Use the base URL to populate the unfilled components until a component is filled
    foreach (['scheme', 'host', 'path', 'query', 'fragment'] as $comp) {
        if (!empty($url_parts[$comp])) {
            break;
        }
        $url_parts[$comp] = $base_parts[$comp];
    }

    return build_url($url_parts);
}

if (!function_exists('get_full_url')) {
    function get_full_url(string $url, string $currentUrl, ?string $baseUrlMeta = null): string
    {
        $baseUrl = get_base_url($currentUrl);

        if (is_url($url)) {
            return $url;
        }

        if (str_starts_with($url, '//')) {
            return "https:{$url}";
        }

        if (str_starts_with($url, '/')) {
            if ($url == '/') {
                return $baseUrl;
            }

            return $baseUrl.$url;
        }

        if (str_starts_with($url, './') && !str_ends_with($currentUrl, '/')) {
            $split = explode('/', $currentUrl);
            $currentUrl = preg_replace("/{$split[count($split) - 1]}/", '', $currentUrl, -1);
        }

        if (str_starts_with($url, '../') && !str_ends_with($currentUrl, '/')) {
            $split = explode('/', $currentUrl);
            $currentUrl = preg_replace("/{$split[count($split) - 2]}/", '', $currentUrl, -1);
        }

        if ($baseUrlMeta) {
            return abs_url("{$baseUrlMeta}/{$url}", $currentUrl);
        }

        return abs_url("{$currentUrl}/{$url}", $currentUrl);
    }
}
