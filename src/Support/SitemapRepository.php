<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\Sitemap;

class SitemapRepository implements Sitemap
{
    protected array $providers = [];

    /**
     * Register a sitemap provider class
     *
     * @param string $key Unique identifier for the sitemap provider
     * @param string $class Class name that implements Sitemapable
     * @return void
     */
    public function register(string $key, string $class): void
    {
        $this->providers[$key] = $class;
    }

    /**
     * Get all registered sitemap providers
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return new Collection($this->providers);
    }

    /**
     * Get a specific sitemap provider
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->providers[$key] ?? null;
    }
}
