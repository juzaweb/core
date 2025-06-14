<?php

namespace Juzaweb\Core\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasChangeBy
{
    /**
     * Boot the HasChangeBy trait for a model.
     *
     * Automatically sets the 'created_by' and 'updated_by' attributes
     * using the currently authenticated user's ID during the saving event.
     */
    public static function bootHasChangeBy(): void
    {
        static::saving(
            function ($model) {
                // Check if a user is authenticated
                if (Auth::check()) {
                    // Set 'created_by' if the column exists and the model is new or 'created_by' is not set
                    if (Schema::hasColumns($model->getTable(), ['created_by'])) {
                        if (empty($model->id) && !$model->getAttribute('created_by')) {
                            $model->created_by = Auth::id();
                        }
                    }

                    // Set 'updated_by' if the column exists and 'updated_by' is not set
                    if (Schema::hasColumns($model->getTable(), ['updated_by'])) {
                        if (!$model->getAttribute('updated_by')) {
                            $model->updated_by = Auth::id();
                        }
                    }
                }
            }
        );
    }

    /**
     * The user who created this model.
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * The user who last updated this model.
     *
     * @return BelongsTo
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
