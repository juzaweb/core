<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Contracts;

use Juzaweb\Core\Support\Routes\Resources\AdminResource;
use Juzaweb\Core\Support\Routes\Resources\APIResource;

interface RouteResource
{
    public function api(string $name, string $controller): APIResource;

    public function admin(string $name, string $controller): AdminResource;
}
