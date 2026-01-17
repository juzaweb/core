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

trait UsedInFrontend
{
    public function scopeWhereInFrontend(Builder $builder, bool $cache = true): Builder
    {
        return $builder;
    }

    public function scopeWhereFrontend(Builder $builder, array $translationCacheTags = []): Builder
    {
        $cache = config('app.optimize.cache_in_frontend', true);

        return $builder
            ->when(
                $cache,
                fn (Builder $query) => $query->cacheFor(3600),
            )
            ->when(
                method_exists($this, 'scopeWhereInFrontend'),
                fn (Builder $query) => $query->whereInFrontend($cache),
            );
    }
}
