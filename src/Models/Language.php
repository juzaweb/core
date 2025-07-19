<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Collection;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\QueryCache\QueryCacheable;
use Juzaweb\Translations\Models\Language as LanguageAlias;

class Language extends LanguageAlias
{
    use HasAPI, QueryCacheable;

    protected $table = 'languages';

    protected array $filterable = ['code', 'name'];

    protected array $searchable = ['code', 'name'];

    protected array $sortable = ['code', 'name'];

    protected array $sortDefault = ['created_at' => 'desc'];

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
}
