<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Support\BreadcrumbFactory;

/**
 * @method static void add(string $title, string $url = null)
 * @method static void items(array $items)
 * @method static void addItems(array $items)
 * @method static array getItems()
 * @method static array toArray()
 *
 * @see BreadcrumbFactory
 */
class Breadcrumb extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\Breadcrumb::class;
    }
}
