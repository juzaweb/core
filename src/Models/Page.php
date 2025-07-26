<?php

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Juzaweb\Core\Models\Enums\PageStatus;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\HasSeoMeta;
use Juzaweb\Translations\Contracts\Translatable as TranslatableContract;
use Juzaweb\Translations\Traits\Translatable;

class Page extends Model implements TranslatableContract
{
    use HasAPI, HasUuids, Translatable, HasSeoMeta;

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

    protected $translatedAttributeFormats = [
        'content' => 'html',
    ];

    protected $casts = [
        'status' => PageStatus::class,
    ];

    public function seoMetaFill(): array
    {
        return [
            $this->defaultLocale => [
                'title' => $this->title,
                'description' => seo_string($this->content, 240),
            ]
        ];
    }
}
