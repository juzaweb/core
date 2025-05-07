<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Models;

/**
 *
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $keywords
 * @property string|null $image
 * @property string|null $locale
 * @property int $seo_meta_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation query()
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereSeoMetaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SeoMetaTranslation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SeoMetaTranslation extends Model
{
    protected $table = 'seo_meta_translations';

    protected $fillable = [
        'title',
        'description',
        'keywords',
        'image',
        'locale',
    ];
}
