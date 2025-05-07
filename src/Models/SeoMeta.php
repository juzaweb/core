<?php

namespace Juzaweb\Core\Models;

use Juzaweb\Core\Translations\Traits\Translatable;

/**
 *
 *
 * @property int $id
 * @property string $seometable_type
 * @property string $seometable_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $seometable
 * @property-read \Juzaweb\Core\Models\SeoMetaTranslation|null $translation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Juzaweb\Core\Models\SeoMetaTranslation> $translations
 * @property-read int|null $translations_count
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta listsTranslations(string $translationField)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta notTranslatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta orWhereTranslation(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta orWhereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta orderByTranslation(string $translationField, string $sortMethod = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta translated()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta translatedIn(?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereSeometableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereSeometableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereTranslationLike(string $translationField, $value, ?string $locale = null)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMeta withTranslation(?string $locale = null)
 * @mixin \Eloquent
 */
class SeoMeta extends Model
{
    use Translatable;

    protected $fillable = [
        'seometable_type',
        'seometable_id',
    ];

    public $translatedAttributes = [
        'title',
        'description',
        'keywords',
        'image',
    ];

    public function seometable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'seometable_type', 'seometable_id');
    }
}
