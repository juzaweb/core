<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasThemeField
{
    public static function bootHasThemeField(): void
    {
        static::creating(function ($model) {
            if (empty($model->theme)) {
                $model->theme = active_theme();
            }
        });

        static::addGlobalScope(
            'theme',
            function (Builder $builder) {
                $builder->where('theme', active_theme());
            }
        );
    }
}
