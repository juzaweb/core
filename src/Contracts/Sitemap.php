<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Support\Collection;

interface Sitemap
{
    /**
     * Register a sitemap provider class
     *
     * @param string $key Unique identifier for the sitemap provider
     * @param string $class Class name that implements Sitemapable
     * @return void
     */
    public function register(string $key, string $class): void;

    /**
     * Get all registered sitemap providers
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Get a specific sitemap provider
     *
     * @param string $key
     * @return string|null
     */
    public function get(string $key): ?string;
}
