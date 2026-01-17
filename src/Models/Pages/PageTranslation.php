<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Models\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\Modules\Core\Contracts\Sitemapable;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\FileManager\Traits\HasMedia;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Traits\HasDescription;
use Juzaweb\Modules\Core\Traits\HasNetworkWebsite;
use Juzaweb\Modules\Core\Traits\HasSitemap;
use Juzaweb\Modules\Core\Traits\HasSlug;
use function Juzaweb\Modules\Admin\Models\Pages\website_id;

class PageTranslation extends Model implements Sitemapable
{
    use HasDescription, HasMedia, HasSlug, HasNetworkWebsite, HasSitemap;

    protected $table = 'page_translations';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'description',
        'locale',
        'thumbnail',
        'website_id',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    public function scopeForSitemap(Builder $builder): Builder
    {
        return $builder
            ->join('pages', 'pages.id', '=', 'page_translations.page_id')
            ->select(['page_translations.*'])
            ->cacheDriver('file')
            ->cacheFor(3600 * 24)
            ->where('pages.status', PageStatus::PUBLISHED)
            ->where('pages.website_id', website_id())
            ->where('pages.id', '!=', theme_setting('home_page'))
            ->orderBy('page_translations.updated_at', 'desc');
    }

    public function getUrl(): string
    {
        if ($this->id === theme_setting('home_page')) {
            return home_url();
        }

        if ($this->locale != setting('language')) {
            return home_url("{$this->locale}/{$this->slug}");
        }

        return home_url($this->slug);
    }

    public function getThumbnailAttribute(): ?string
    {
        return $this->getFirstMedia('thumbnail')?->path;
    }

    public function setThumbnailAttribute($value): void
    {
        if (is_null($value)) {
            $this->detachMedia('thumbnail');
            return;
        }

        $this->attachMedia($value, 'thumbnail');
    }
}
