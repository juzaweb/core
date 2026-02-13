<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();

        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'is_super_admin' => 1,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->user);
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * Test admin profile index page
     */
    public function testProfileIndex()
    {
        $response = $this->get(route('admin.profile'));

        $response->assertStatus(200)
            ->assertViewIs('core::admin.profile.index')
            ->assertViewHas('user', $this->user);
    }

    /**
     * Test update profile with valid data
     */
    public function testUpdateProfileWithValidData()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/profile", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
        ]);
    }

    /**
     * Test update profile with password
     */
    public function testUpdateProfileWithPassword()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/profile", [
            'name' => 'Updated Name',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $updatedUser = $this->user->fresh();

        $this->assertEquals('Updated Name', $updatedUser->name);
        $this->assertTrue(Hash::check('newpassword123', $updatedUser->password));
    }

    /**
     * Test update profile requires name
     */
    public function testUpdateProfileRequiresName()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/profile", [
            'name' => '',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test update profile password confirmation must match
     */
    public function testUpdateProfilePasswordConfirmationMustMatch()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/profile", [
            'name' => 'Updated Name',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test update profile password minimum length
     */
    public function testUpdateProfilePasswordMinimumLength()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/profile", [
            'name' => 'Updated Name',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test unauthenticated user cannot access profile
     */
    public function testUnauthenticatedUserCannotAccessProfile()
    {
        Auth::logout();

        $response = $this->get(route('admin.profile'));

        $response->assertStatus(302);
    }

    /**
     * Test unauthenticated user cannot update profile
     */
    public function testUnauthenticatedUserCannotUpdateProfile()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        Auth::logout();

        $response = $this->postJson("/{$adminPrefix}/profile", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(401);
    }
}
