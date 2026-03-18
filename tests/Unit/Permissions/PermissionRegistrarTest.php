<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Permissions;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\Permissions\PermissionRegistrar;
use Juzaweb\Modules\Core\Tests\TestCase;

class PermissionRegistrarTest extends TestCase
{
    protected PermissionRegistrar $registrar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->registrar = $this->app->make(PermissionRegistrar::class);
    }

    public function test_set_permissions_team_id_with_int(): void
    {
        $this->registrar->setPermissionsTeamId(123);

        $this->assertEquals(123, $this->registrar->getPermissionsTeamId());
    }

    public function test_set_permissions_team_id_with_string(): void
    {
        $this->registrar->setPermissionsTeamId('team-1');

        $this->assertEquals('team-1', $this->registrar->getPermissionsTeamId());
    }

    public function test_set_permissions_team_id_with_model(): void
    {
        $model = new class extends Model
        {
            protected $primaryKey = 'id';

            public function getKey()
            {
                return 456;
            }
        };

        $this->registrar->setPermissionsTeamId($model);

        $this->assertEquals(456, $this->registrar->getPermissionsTeamId());
    }

    public function test_get_permissions_team_id_returns_null_by_default(): void
    {
        $this->assertNull($this->registrar->getPermissionsTeamId());
    }
}
