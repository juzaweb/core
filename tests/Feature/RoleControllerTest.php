<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Core\Permissions\Models\Role;
use Juzaweb\Modules\Core\Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->user);
    }

    public function test_index_roles()
    {
        $response = $this->get(admin_url('roles'));

        $response->assertStatus(200);
    }

    public function test_create_role()
    {
        $response = $this->get(admin_url('roles/create'));

        $response->assertStatus(200);
    }

    public function test_store_role()
    {
        $response = $this->postJson(admin_url('roles'), [
            'name' => 'Test Role',
            'code' => 'test-role',
            'guard_name' => 'web',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Test Role',
            'code' => 'test-role',
        ]);
    }

    public function test_store_role_requires_name()
    {
        $response = $this->postJson(admin_url('roles'), [
            'code' => 'test-role',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_store_role_requires_code()
    {
        $response = $this->postJson(admin_url('roles'), [
            'name' => 'Test Role',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    public function test_store_role_requires_unique_code()
    {
        Role::create([
            'name' => 'Existing Role',
            'code' => 'existing-role',
            'guard_name' => 'web',
        ]);

        $response = $this->postJson(admin_url('roles'), [
            'name' => 'New Role',
            'code' => 'existing-role',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    public function test_update_role()
    {
        $role = Role::create([
            'name' => 'Old Role',
            'code' => 'old-role',
            'guard_name' => 'web',
        ]);

        $response = $this->putJson(admin_url('roles/'.$role->id), [
            'name' => 'Updated Role',
            'code' => 'old-role',
            'guard_name' => 'web',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Role',
        ]);
    }

    public function test_delete_role()
    {
        $role = Role::create([
            'name' => 'Delete Role',
            'code' => 'delete-role',
            'guard_name' => 'web',
        ]);

        $response = $this->deleteJson(admin_url('roles/'.$role->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }

    public function test_bulk_delete_roles()
    {
        $role1 = Role::create([
            'name' => 'Bulk Role 1',
            'code' => 'bulk-role-1',
            'guard_name' => 'web',
        ]);

        $role2 = Role::create([
            'name' => 'Bulk Role 2',
            'code' => 'bulk-role-2',
            'guard_name' => 'web',
        ]);

        $response = $this->postJson(admin_url('roles/bulk'), [
            'ids' => [$role1->id, $role2->id],
            'action' => 'delete',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('roles', ['id' => $role1->id]);
        $this->assertDatabaseMissing('roles', ['id' => $role2->id]);
    }
}
