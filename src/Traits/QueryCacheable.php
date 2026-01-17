<?php

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

trait QueryCacheable
{
    use \Juzaweb\QueryCache\QueryCacheable;

    public function scopeOneQueryWithCache(Builder $builder, string $id): Builder
    {
        return $builder->cacheTags([$this->getTable().":{$id}"]);
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
        $table = $this->getTable();

        return [
            ...$this->getCacheBaseTags(),
            $table . ':' . $this->id,
        ];
    }
}
