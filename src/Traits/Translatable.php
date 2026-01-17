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

trait Translatable
{
    use \Juzaweb\Modules\Core\Translations\Traits\Translatable;

    public function scopeWithTranslationAndMedia(Builder $query, ?string $locale = null, ?array $with = null): Builder
    {
        $with = array_merge($with ?? [], ['media']);

        return $this->scopeWithTranslation($query, $locale, $with);
    }
}
