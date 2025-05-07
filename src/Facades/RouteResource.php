<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Core\Routes\Resources\AdminResource;
use Juzaweb\Core\Routes\Resources\APIResource;

/**
 * @method static APIResource api(string $name, string $controller)
 * @method static AdminResource admin(string $name, string $controller)
 */
class RouteResource extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Core\Contracts\RouteResource::class;
    }
}
