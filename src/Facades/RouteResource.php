<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Support\Routes\Resources\AdminResource;
use Juzaweb\Modules\Core\Support\Routes\Resources\APIResource;

/**
 * @method static APIResource api(string $name, string $controller)
 * @method static AdminResource admin(string $name, string $controller)
 * @see \Juzaweb\Modules\Core\Support\RouteResourceRepository
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
        return \Juzaweb\Modules\Core\Contracts\RouteResource::class;
    }
}
