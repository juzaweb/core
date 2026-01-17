<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\QueryCache\QueryCacheable;

class Language extends Model
{
    use QueryCacheable, HasAPI;

    protected $table = 'languages';

    protected $fillable = [
        'code',
        'name',
    ];

    public static function languages(): Collection
    {
        return self::cacheFor(config('core.query_cache.lifetime'))
            ->get()
            ->map(function ($item) {
                $item->regional = config("locales.{$item->code}.regional");
                $item->country = explode('_', strtolower($item->regional))[1] ?? null;
                return $item;
            })
            ->keyBy('code');
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
}
