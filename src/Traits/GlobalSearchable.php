<?php

namespace Juzaweb\Core\Traits;

use App\GlobalSearch;
use App\Observers\GlobalSearchObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @mixin Model
 */
trait GlobalSearchable
{
    /**
     * Boot the global searchable functionality for the model.
     *
     * This function registers an observer for the model to observe changes in the model's data.
     * The observer class used is GlobalSearchObserver.
     *
     * @return void
     */
    public static function bootGlobalSearchable(): void
    {
        static::observe(GlobalSearchObserver::class);
    }

    /**
     * Retrieve the morph many relationship for the global search.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function globalSearch(): MorphMany
    {
        return $this->morphMany(GlobalSearch::class, 'model', 'model_type', 'model_id');
    }

    abstract public function globalSeachFields(): array;
}
