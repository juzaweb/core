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

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Contracts\Sitemap as SitemapContract;
use Juzaweb\Modules\Core\Support\SitemapRepository;

/**
 * @method static void register(string $key, string $class)
 * @method static Collection all()
 * @method static string|null get(string $key)
 *
 * @see SitemapRepository
 */
class Sitemap extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return SitemapContract::class;
    }
}
