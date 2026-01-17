<?php

namespace Juzaweb\Modules\Core\Models\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\Modules\Admin\Database\Factories\PageFactory;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\FileManager\Traits\HasMedia;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Support\Traits\MenuBoxable;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\HasContent;
use Juzaweb\Modules\Core\Traits\HasSeoMeta;
use Juzaweb\Modules\Core\Traits\HasThumbnail;
use Juzaweb\Modules\Core\Traits\Translatable;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable as TranslatableContract;

class Page extends Model implements TranslatableContract
{
    use HasAPI,
        HasFactory,
        HasUuids,
        Translatable,
        HasSeoMeta,
        MenuBoxable,
        UsedInFrontend,
        HasMedia,
        HasThumbnail,
        HasContent;

    protected $table = 'pages';

    protected $fillable = [
        'status',
        'template',
    ];

    protected $translatedAttributes = [
        'title',
        'slug',
        'content',
        'description',
        'locale',
    ];

    protected $translatedAttributeFormats = [
        'content' => 'html',
    ];

    protected $casts = [
        'status' => PageStatus::class,
    ];

    public $mediaChannels = ['thumbnail'];

    public static function home()
    {
        if ($homeId = theme_setting('home_page')) {
            return static::find($homeId);
        }

        return null;
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class, 'page_id', 'id');
    }

    public function scopeWhereInMenuBox(Builder $builder): Builder
    {
        return $builder
            ->withTranslation()
            ->where('status', PageStatus::PUBLISHED);
    }

    public function scopeAdditionSearch(Builder $builder, string $keyword): Builder
    {
        return $builder->orWhereHas(
            'translations',
            function (Builder $query) use ($keyword) {
                $query->where('title', 'like', '%' . $keyword . '%');
            }
        );
    }

    public function scopeWhereInFrontend(Builder $builder): Builder
    {
        return $builder
            ->withTranslation(null, ['media'], true)
            ->cacheFor(3600)
            ->where('status', PageStatus::PUBLISHED);
    }

    public function getUrl(): string
    {
        if ($this->id === theme_setting('home_page')) {
            return home_url(locale: $this->locale);
        }

        return home_url($this->slug, $this->locale);
    }

    public function getEditUrl(): string
    {
        return route('admin.pages.edit', [website_id(), $this->id]);
    }

    public function seoMetaFill(): array
    {
        return [
            $this->defaultLocale => [
                'title' => $this->title,
                'description' => seo_string($this->content, 240),
            ]
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): PageFactory
    {
        return PageFactory::new();
    }
}
