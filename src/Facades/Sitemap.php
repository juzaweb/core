<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Contracts\Sitemap as SitemapContract;

/**
 * @method static void register(string $key, string $class)
 * @method static Collection all()
 * @method static string|null get(string $key)
 * @see \Juzaweb\Modules\Core\Support\SitemapRepository
 */
class Sitemap extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SitemapContract::class;
    }
}
