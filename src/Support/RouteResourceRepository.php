<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Illuminate\Contracts\Routing\Registrar;
use Juzaweb\Core\Contracts\RouteResource;
use Juzaweb\Core\Support\Routes\Resources\AdminResource;
use Juzaweb\Core\Support\Routes\Resources\APIResource;

class RouteResourceRepository implements RouteResource
{
    public function __construct(protected Registrar $registrar)
    {
        //
    }

    public function admin(string $name, string $controller): AdminResource
    {
        return new AdminResource($this->registrar, $name, $controller);
    }

    public function api(string $name, string $controller): APIResource
    {
        return new APIResource($this->registrar, $name, $controller);
    }
}
