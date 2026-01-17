<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Modules\Core\Traits\HasThemeField;
use Juzaweb\Modules\Core\Traits\Translatable;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;

class ThemeSidebar extends Model
{
    use Translatable,  HasUuids, HasThemeField, UsedInFrontend;

    protected $table = 'theme_sidebars';

    protected $fillable = [
        'sidebar',
        'widget',
        'data',
        'theme',
        'display_order',
        'website_id',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public $translatedAttributes = [
        'label',
        'fields',
        'locale',
    ];

    public function scopeWhereInFrontend(Builder $builder, bool $cache): Builder
    {
        return $builder->when(
            $cache,
            fn(Builder $query) => $query->cacheFor(3600),
        )
            ->withTranslation(null, null, $cache);
    }
}
