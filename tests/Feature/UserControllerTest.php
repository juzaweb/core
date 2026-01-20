<?php


namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Juzaweb\Modules\Admin\Enums\UserStatus;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->user);
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * Test store user with valid data
     */
    public function testStoreUserWithValidData()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/users", [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);
    }

    /**
     * Test store user requires name
     */
    public function testStoreUserRequiresName()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/users", [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test store user requires email
     */
    public function testStoreUserRequiresEmail()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/users", [
            'name' => 'New User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test store user requires password
     */
    public function testStoreUserRequiresPassword()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/users", [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test store user requires unique email
     */
    public function testStoreUserRequiresUniqueEmail()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson("/{$adminPrefix}/users", [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test store user requires password confirmation
     */
    public function testStoreUserRequiresPasswordConfirmation()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/users", [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test update user with valid data
     */
    public function testUpdateUserWithValidData()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'is_super_admin' => false,
        ]);

        $response = $this->putJson("/{$adminPrefix}/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /**
     * Test update user with password
     */
    public function testUpdateUserWithPassword()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user = User::factory()->create([
            'is_super_admin' => false,
        ]);

        $response = $this->putJson("/{$adminPrefix}/users/{$user->id}", [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $updatedUser = $user->fresh();
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
    }

    /**
     * Test cannot update super admin user
     */
    public function testCannotUpdateSuperAdminUser()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $superAdmin = User::factory()->create([
            'is_super_admin' => true,
        ]);

        $response = $this->putJson("/{$adminPrefix}/users/{$superAdmin->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(404);
    }

    /**
     * Test delete user
     */
    public function testDeleteUser()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user = User::factory()->create([
            'is_super_admin' => false,
        ]);

        $response = $this->deleteJson("/{$adminPrefix}/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * Test cannot delete super admin user
     */
    public function testCannotDeleteSuperAdminUser()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $superAdmin = User::factory()->create([
            'is_super_admin' => true,
        ]);

        $response = $this->deleteJson("/{$adminPrefix}/users/{$superAdmin->id}");

        $response->assertStatus(404);

        $this->assertDatabaseHas('users', [
            'id' => $superAdmin->id,
        ]);
    }

    /**
     * Test bulk delete users
     */
    public function testBulkDeleteUsers()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user1 = User::factory()->create(['is_super_admin' => false]);
        $user2 = User::factory()->create(['is_super_admin' => false]);

        $response = $this->postJson("/{$adminPrefix}/users/bulk", [
            'ids' => [$user1->id, $user2->id],
            'action' => 'delete',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('users', ['id' => $user1->id]);
        $this->assertDatabaseMissing('users', ['id' => $user2->id]);
    }

    /**
     * Test bulk activate users
     */
    public function testBulkActivateUsers()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user1 = User::factory()->create(['is_super_admin' => false, 'status' => UserStatus::INACTIVE]);
        $user2 = User::factory()->create(['is_super_admin' => false, 'status' => UserStatus::BANNED]);

        $response = $this->postJson("/{$adminPrefix}/users/bulk", [
            'ids' => [$user1->id, $user2->id],
            'action' => 'activate',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'status' => UserStatus::ACTIVE->value,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'status' => UserStatus::ACTIVE->value,
        ]);
    }

    /**
     * Test bulk deactivate users
     */
    public function testBulkDeactivateUsers()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user1 = User::factory()->create(['is_super_admin' => false, 'status' => UserStatus::ACTIVE]);
        $user2 = User::factory()->create(['is_super_admin' => false, 'status' => UserStatus::ACTIVE]);

        $response = $this->postJson("/{$adminPrefix}/users/bulk", [
            'ids' => [$user1->id, $user2->id],
            'action' => 'deactivate',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'status' => UserStatus::INACTIVE->value,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'status' => UserStatus::INACTIVE->value,
        ]);
    }

    /**
     * Test bulk ban users
     */
    public function testBulkBanUsers()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user1 = User::factory()->create(['is_super_admin' => false, 'status' => UserStatus::ACTIVE]);
        $user2 = User::factory()->create(['is_super_admin' => false, 'status' => UserStatus::INACTIVE]);

        $response = $this->postJson("/{$adminPrefix}/users/bulk", [
            'ids' => [$user1->id, $user2->id],
            'action' => 'banned',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'status' => UserStatus::BANNED->value,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'status' => UserStatus::BANNED->value,
        ]);
    }

    /**
     * Test bulk cannot delete super admin users
     */
    public function testBulkCannotDeleteSuperAdminUsers()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $superAdmin = User::factory()->create(['is_super_admin' => true]);
        $normalUser = User::factory()->create(['is_super_admin' => false]);

        $response = $this->postJson("/{$adminPrefix}/users/bulk", [
            'ids' => [$superAdmin->id, $normalUser->id],
            'action' => 'delete',
        ]);

        $response->assertStatus(200);

        // Super admin should still exist
        $this->assertDatabaseHas('users', [
            'id' => $superAdmin->id,
        ]);

        // Normal user should be deleted
        $this->assertDatabaseMissing('users', [
            'id' => $normalUser->id,
        ]);
    }

    /**
     * Test bulk with invalid action returns error
     */
    public function testBulkWithInvalidActionReturnsError()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $user = User::factory()->create(['is_super_admin' => false]);

        $response = $this->postJson("/{$adminPrefix}/users/bulk", [
            'ids' => [$user->id],
            'action' => 'invalid_action',
        ]);

        $response->assertJson([
            'success' => false,
        ]);
    }
}
