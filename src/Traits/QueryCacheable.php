<?php

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Juzaweb\Core\Support\Queries\Builder;
use Juzaweb\Core\Support\Queries\FlushQueryCacheObserver;

/**
 * @property bool $flushCacheOnUpdate
 * @property bool $globalQueryCacheWhenEnable
 * @property bool $globalQueryCache
 * @method static bool flushQueryCache(array $tags = [])
 * @method static bool flushQueryCacheWithTag(string $string)
 * @method static \Illuminate\Database\Query\Builder|static cacheFor(\DateTime|int|null $time)
 * @method static \Illuminate\Database\Query\Builder|static cacheForever()
 * @method static \Illuminate\Database\Query\Builder|static dontCache()
 * @method static \Illuminate\Database\Query\Builder|static doNotCache()
 * @method static \Illuminate\Database\Query\Builder|static cachePrefix(string $prefix)
 * @method static \Illuminate\Database\Query\Builder|static cacheTags(array $cacheTags = [])
 * @method static \Illuminate\Database\Query\Builder|static appendCacheTags(array $cacheTags = [])
 * @method static \Illuminate\Database\Query\Builder|static cacheDriver(string $cacheDriver)
 * @method static \Illuminate\Database\Query\Builder|static cacheBaseTags(array $tags = [])
 */
trait QueryCacheable
{
    protected static bool $globalQueryCacheWhenEnable = false;

    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootQueryCacheable(): void
    {
        $flushCacheOnUpdate = static::getFlushCacheOnUpdate();

        if ($flushCacheOnUpdate) {
            static::observe(static::getFlushQueryCacheObserver());
        }
    }

    public static function getFlushCacheOnUpdate(): bool
    {
        return !isset(static::$flushCacheOnUpdate) || static::$flushCacheOnUpdate;
    }

    public static function disableFlushCacheOnUpdate(): void
    {
        static::$flushCacheOnUpdate = false;
    }

    public static function globalQueryCacheWhen(bool $cache): void
    {
        static::$globalQueryCacheWhenEnable = $cache;
    }

    /**
     * Get the observer class name that will
     * observe the changes and will invalidate the cache
     * upon database change.
     *
     * @return string
     */
    protected static function getFlushQueryCacheObserver(): string
    {
        return FlushQueryCacheObserver::class;
    }

    /**
     * Set the base cache tags that will be present
     * on all queries.
     *
     * @return array
     */
    protected function getCacheBaseTags(): array
    {
        return [
            $this->getTable(),
        ];
    }

    /**
     * This function returns a boolean value indicating whether the global query cache is enabled.
     *
     * @return bool Returns `false` to indicate that the global query cache is disabled and queries will not be cached
     * at the global level.
     */
    protected function getGlobalQueryCache(): bool
    {
        return (isset($this->globalQueryCache) && $this->globalQueryCache)
            || (isset(static::$globalQueryCacheWhenEnable) && static::$globalQueryCacheWhenEnable);
    }

    /**
     * @return \DateTime|int|null
     */
    protected function getGlobalQueryCacheTime()
    {
        return 60 * 60 * 24;
    }

    /**
     * When invalidating automatically on update, you can specify
     * which tags to invalidate.
     *
     * @param  string|null  $relation
     * @param  Collection|null  $pivotedModels
     * @return array
     */
    public function getCacheTagsToInvalidateOnUpdate(
        ?string $relation = null,
        ?Collection $pivotedModels = null
    ): array {
        return $this->getCacheBaseTags();
    }

    /** For compoships */
    public function getAttribute($key)
    {
        if (is_array($key)) { //Check for multi-columns relationship
            return array_map(
                function ($k) {
                    return parent::getAttribute($k);
                },
                $key
            );
        }

        return parent::getAttribute($key);
    }

    public function qualifyColumn($column)
    {
        if (is_array($column)) { //Check for multi-column relationship
            return array_map(
                function ($c) {
                    if (Str::contains($c, '.')) {
                        return $c;
                    }

                    return $this->getTable().'.'.$c;
                },
                $column
            );
        }

        return parent::qualifyColumn($column);
    }

    /** For compoships */

    protected function newBaseQueryBuilder(): Builder
    {
        $connection = $this->getConnection();

        $builder = new Builder(
            $connection,
            $connection->getQueryGrammar(),
            $connection->getPostProcessor()
        );

        $builder->dontCache();

        if ($this->cacheFor) {
            $builder->cacheFor($this->cacheFor);
        }

        if (!$this->cacheFor && $this->getGlobalQueryCache()) {
            $builder->cacheFor($this->getGlobalQueryCacheTime());
        }

        if (method_exists($this, 'cacheForValue')) {
            $builder->cacheFor($this->cacheForValue($builder));
        }

        if ($this->cacheTags) {
            $builder->cacheTags($this->cacheTags);
        }

        if (method_exists($this, 'cacheTagsValue')) {
            $builder->cacheTags($this->cacheTagsValue($builder));
        }

        if ($this->cachePrefix) {
            $builder->cachePrefix($this->cachePrefix);
        }

        if (method_exists($this, 'cachePrefixValue')) {
            $builder->cachePrefix($this->cachePrefixValue($builder));
        }

        if ($this->cacheDriver) {
            $builder->cacheDriver($this->cacheDriver);
        }

        if (method_exists($this, 'cacheDriverValue')) {
            $builder->cacheDriver($this->cacheDriverValue($builder));
        }

        if ($this->cacheUsePlainKey) {
            $builder->withPlainKey();
        }

        if (method_exists($this, 'cacheUsePlainKeyValue')) {
            $builder->withPlainKey($this->cacheUsePlainKeyValue($builder));
        }

        return $builder->cacheBaseTags($this->getCacheBaseTags());
    }
}
