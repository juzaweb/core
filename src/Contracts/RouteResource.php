<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Contracts;

use Juzaweb\Core\Routes\Resources\AdminResource;
use Juzaweb\Core\Routes\Resources\APIResource;

interface RouteResource
{
    public function api(string $name, string $controller): APIResource;

    public function admin(string $name, string $controller): AdminResource;
}
