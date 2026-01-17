<?php

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * @property bool $cacheForApi
 * @property int|null|\DateTime $cacheForApiTime // The number of seconds or the DateTime instance that specifies how long to cache the query.
 * @method static Builder|static api(array $params = [])
 * @method static Builder|static inApi(array $params = []) // TODO: Add your custom scopes here
 */
trait HasAPI
{
    use HasResource, Filterable, Sortable, Searchable;

    public function apiWithDefaults(): array
    {
        return [];
    }

    /**
     * API scope, call this scope for API query
     *
     * @param  Builder|static  $builder
     * @param  array  $params
     * @return Builder
     */
    public function scopeApi(Builder $builder, array $params = []): Builder
    {
        return $builder->with($this->apiWithDefaults())
            ->when(
                method_exists($this, 'scopeInApi'),
                fn (Builder $query) => $query->inApi($params)
            )
            ->when(
                method_exists($this, 'scopeInApiGuest') && auth()->guest(),
                fn (Builder $query) => $query->inApiGuest($params)
            )
            ->when(
                property_exists($this, 'cacheForApi') && $this->cacheForApi,
                fn (Builder $query) => $query->cacheFor($this->getCacheForApiTime())
            )
            ->searchAndFilter($params)
            ->sort($params);
    }

    public function scopeSearchAndFilter(Builder $builder, array $params = [])
    {
        return $builder
            ->when(
                $keyword = Arr::get($params, 'q'),
                fn (Builder $query) => $query->search($keyword)
            )
            ->filter($params);
    }

    /**
     * Get the cache duration for API queries.
     *
     * This method returns the cache duration for API queries, either as an integer representing
     * the number of seconds or as a DateTime instance. If the `cacheForApiTime` property exists
     * on the instance, its value is returned. Otherwise, a default cache duration of 3600 seconds
     * (1 hour) is returned.
     *
     * @return int|\DateTime The cache duration for API queries.
     */
    protected function getCacheForApiTime(): int|\DateTime
    {
        if (property_exists($this, 'cacheForApiTime')) {
            return $this->cacheForApiTime;
        }

        // Default cache for 1 hour
        return 3600;
    }
}
