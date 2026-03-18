<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Support\Collection;

interface Sitemap
{
    /**
     * Register a sitemap provider class
     *
     * @param  string  $key  Unique identifier for the sitemap provider
     * @param  string  $class  Class name that implements Sitemapable
     */
    public function register(string $key, string $class): void;

    /**
     * Get all registered sitemap providers
     */
    public function all(): Collection;

    /**
     * Get a specific sitemap provider
     */
    public function get(string $key): ?string;
}
