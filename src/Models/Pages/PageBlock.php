<?php

namespace Juzaweb\Modules\Core\Models\Pages;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasThemeField;
use Juzaweb\Modules\Core\Traits\Translatable;

class PageBlock extends Model
{
    use Translatable, HasUuids, HasThemeField;

    protected $table = 'page_blocks';

    protected $fillable = [
        'page_id',
        'block',
        'data',
        'theme',
        'container',
        'display_order',
    ];

    protected $casts = [
        'data' => 'json',
    ];

    public $translatedAttributes = [
        'label',
        'fields',
        'locale',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
