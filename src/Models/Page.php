<?php

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Enums\PageStatus;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Translations\Contracts\Translatable as TranslatableAlias;
use Juzaweb\Translations\Traits\Translatable;

class Page extends Model implements TranslatableAlias
{
    use HasAPI, HasUuids, Translatable;

    protected $table = 'pages';

    protected $fillable = [
        'status',
    ];

    protected $translatedAttributes = [
        'title',
        'slug',
        'content',
        'locale',
        'thumbnail',
    ];

    protected $casts = [
        'status' => PageStatus::class,
    ];
}
