<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations\Models;

use Illuminate\Database\Eloquent\Collection;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasAPI;

class Language extends Model
{
    use HasAPI;

    protected $table = 'languages';

    protected $fillable = [
        'code',
        'name',
    ];

    protected array $filterable = ['code', 'name'];

    protected array $searchable = ['code', 'name'];

    protected array $sortable = ['code', 'name'];

    protected array $sortDefault = ['created_at' => 'desc'];

    public static function languages(): Collection
    {
        return self::cacheFor(config('app.query_cache.lifetime'))
            ->get()
            ->map(function ($item) {
                $item->regional = config("locales.{$item->code}.regional");
                $item->country = explode('_', strtolower($item->regional))[1] ?? null;
                return $item;
            })
            ->keyBy('code');
    }

    public static function default(): string
    {
        return setting('language', config('translatable.fallback_locale'));
    }

    public static function codes(): array
    {
        return static::languages()->keys()->toArray();
    }

    public static function codesWithoutFallback(): array
    {
        return static::languages()->keys()->filter(
            fn ($locale) => $locale !== config('translatable.fallback_locale')
        )->toArray();
    }

    public static function existsCode(string $code): bool
    {
        return self::whereCode($code)->exists();
    }

    public static function findCode(string $code): ?self
    {
        return self::whereCode($code)->first();
    }

    public function getChangeUrl($languages = null): string
    {
        $languages = $languages ?? self::languages();
        $multipleLanguageConfig = setting('multiple_language', 'none');
        $languageCodes = $languages->keys()->toArray();
        $code = $this->code;
        $url = request()->url();
        if ($multipleLanguageConfig === 'session') {
            $url = request()->fullUrlWithQuery(['hl' => $code]);
        } elseif ($multipleLanguageConfig === 'prefix') {
            // $segments = request()->segments();
            // if (in_array($segments[0] ?? '', $languageCodes)) {
            //     array_shift($segments);
            // }

            // $path = $code . (count($segments) > 0 ? '/' . implode('/', $segments) : '');
            // $url = url($path) . (request()->getQueryString() ? '?' . request()->getQueryString() : '');

            if ($code === static::default()) {
                $url = url('/?hl=' . $code);
            } else {
                $url = url($code . '/?hl=' . $code);
            }
        } elseif ($multipleLanguageConfig === 'subdomain') {
            $host = request()->getHost();
            $hostParts = explode('.', $host);
            if (in_array($hostParts[0] ?? '', $languageCodes)) {
                $hostParts[0] = $code;
            } else {
                array_unshift($hostParts, $code);
            }
            $newHost = implode('.', $hostParts);
            $url = request()->getScheme() . '://' . $newHost; // . request()->getRequestUri();
        }

        return $url;
    }
}
