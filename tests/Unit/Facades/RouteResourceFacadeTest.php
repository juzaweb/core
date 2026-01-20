<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\RouteResource as RouteResourceContract;
use Juzaweb\Modules\Core\Facades\RouteResource;
use Juzaweb\Modules\Core\Support\RouteResourceRepository;
use Juzaweb\Modules\Core\Support\Routes\Resources\AdminResource;
use Juzaweb\Modules\Core\Support\Routes\Resources\APIResource;
use Juzaweb\Modules\Core\Tests\TestCase;

class RouteResourceFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(RouteResourceRepository::class, RouteResource::getFacadeRoot());
        $this->assertInstanceOf(RouteResourceContract::class, RouteResource::getFacadeRoot());
    }

    public function test_admin_method_returns_admin_resource()
    {
        $resource = RouteResource::admin('users', 'UserController');

        $this->assertInstanceOf(AdminResource::class, $resource);
    }

    public function test_api_method_returns_api_resource()
    {
        $resource = RouteResource::api('users', 'UserController');

        $this->assertInstanceOf(APIResource::class, $resource);
    }
}
