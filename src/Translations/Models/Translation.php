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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Translation extends Model
{
    public $timestamps = false;

    protected $table = 'translations';

    protected $fillable = [
        'locale',
        'group',
        'namespace',
        'key',
        'value',
        'object_type',
        'object_key',
    ];

    public function sameKeyTranslations(): HasMany
    {
        return $this->hasMany(static::class, 'key', 'key');
    }

    public function scopeOfTranslatedGroup(Builder $query, $group): Builder
    {
        return $query->where('group', $group)->whereNotNull('value');
    }

    public function scopeOrderByGroupKeys(Builder $query, $ordered): Builder
    {
        if ($ordered) {
            $query->orderBy('group')->orderBy('key');
        }

        return $query;
    }

    public function scopeSelectDistinctGroup(Builder $query): Builder
    {
        $select = match (DB::getDriverName()) {
            'mysql' => 'DISTINCT `group`',
            default => 'DISTINCT "group"',
        };

        return $query->select(DB::raw($select));
    }

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('status', '=', 1);
    }
}
