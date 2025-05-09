<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Juzaweb\Core\Traits\HasAPI;

class Language extends Model
{
    use HasAPI;

    protected $keyType = 'string';

    protected $primaryKey = 'code';

    public string $cachePrefix = 'languages_';

    protected $table = 'languages';

    protected $fillable = [
        'code',
        'name',
        'default',
    ];

    protected $casts = [
        'default' => 'bool',
    ];

    protected array $filterable = ['code', 'name'];

    protected array $searchable = ['code', 'name'];

    protected array $sortable = ['code', 'name'];

    protected array $sortDefault = ['code' => 'asc'];

    public static function existsCode(string $code): bool
    {
        return self::whereCode($code)->exists();
    }

    public static function setDefault(string $code): void
    {
        setting()->set('language', $code);
    }

    public static function languages(): Collection
    {
        return self::cacheFor(config('core.query_cache.lifetime'))
            ->get()
            ->keyBy('code');
    }

    public static function default(): ?self
    {
        return self::cacheFor(config('core.query_cache.lifetime'))
            ->where(['code' => setting('language', config('app.locale'))])
            ->first();
    }

    public function scopeIsDefault(Builder $query): Builder
    {
        return $query->where(['default' => true]);
    }

    public function isDefault(): bool
    {
        return $this->code == setting('language', config('app.locale'));
    }
}
