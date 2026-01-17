<?php

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Modules\Core\Traits\HasCreator;

class Rating extends Model
{
    use HasCreator;

    protected $table = 'ratings';

    protected $fillable = [
        'ratingable_type',
        'ratingable_id',
        'created_by',
        'created_type',
        'star',
    ];

    public function ratingable(): MorphTo
    {
        return $this->morphTo();
    }
}
