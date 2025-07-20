<?php

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Translations\Traits\Translatable;

class SeoMeta extends Model
{
    use Translatable;

    protected $fillable = [
        'seometable_type',
        'seometable_id',
    ];

    public $translatedAttributes = [
        'title',
        'description',
        'keywords',
        'image',
    ];

    public function seometable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'seometable_type', 'seometable_id');
    }
}
