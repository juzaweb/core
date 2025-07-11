<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Support\Routes\Resources;

use Illuminate\Contracts\Routing\Registrar;
use Juzaweb\Core\Contracts\RouteResource;

class RouteResourceRepository implements RouteResource
{
    public function __construct(protected Registrar $registrar)
    {
        //
    }

    public function api(string $name, string $controller): APIResource
    {
        return new APIResource($this->registrar, $name, $controller);
    }

    public function admin(string $name, string $controller): AdminResource
    {
        return new AdminResource($this->registrar, $name, $controller);
    }
}
