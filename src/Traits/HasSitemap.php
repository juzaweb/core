<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Models\Model;
use Spatie\Sitemap\Tags\Url;

/**
 * Trait to make models compatible with sitemap generation
 *
 * Usage:
 * class MyModel extends Model implements Sitemapable
 * {
 *     use HasSitemap;
 *
 *     // Optionally override default implementations
 *     protected string $sitemapRoute = 'my-model.show';
 *     protected string $sitemapRouteParam = 'slug';
 * }
 *
 * @mixin Model
 */
trait HasSitemap
{
    /**
     * Scope query for sitemap items
     * Override this method to customize which items appear in sitemap
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeForSitemap(Builder $builder): Builder
    {
        // Default: return all records ordered by updated_at
        return $builder
            ->cacheDriver('file')
            ->cacheFor(3600 * 24)
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Get the sitemap page identifier
     * Uses table name as slug by default
     *
     * @return string
     */
    public static function getSitemapPage(): string
    {
        return Str::slug((new static())->getTable());
    }

    /**
     * Convert model to sitemap URL tag
     * Override this method for custom URL generation
     *
     * @return Url|string|array
     */
    public function toSitemapTag(): Url|string|array
    {
        $route = $this->getSitemapRoute();
        $param = $this->getSitemapRouteParam();

        if ($route && isset($this->{$param})) {
            return Url::create(route($route, [$param => $this->{$param}]))
                ->setLastModificationDate($this->updated_at ?? now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8);
        }

        // Fallback to model URL if available
        if (method_exists($this, 'getUrl')) {
            return Url::create($this->getUrl())
                ->setLastModificationDate($this->updated_at ?? now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8);
        }

        // Final fallback to homepage
        return Url::create(url('/'))
            ->setLastModificationDate($this->updated_at ?? now());
    }

    /**
     * Get the route name for this model's detail page
     * Can be overridden by setting $sitemapRoute property
     *
     * @return string|null
     */
    protected function getSitemapRoute(): ?string
    {
        return $this->sitemapRoute ?? null;
    }

    /**
     * Get the route parameter name (e.g., 'slug', 'id')
     * Can be overridden by setting $sitemapRouteParam property
     *
     * @return string
     */
    protected function getSitemapRouteParam(): string
    {
        return $this->sitemapRouteParam ?? 'slug';
    }
}
