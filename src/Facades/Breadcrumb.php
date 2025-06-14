<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void add(string $label, ?string $url = null)
 * @method static void items(array $items)
 * @method static void addItems(array $items)
 * @method static array getItems()
 * @see BreadcrumbFactory
 */
class Breadcrumb extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Core\Contracts\Breadcrumb::class;
    }
}
