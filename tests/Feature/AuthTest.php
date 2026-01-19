<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class AuthTest extends TestCase
{
    use WithoutMiddleware, DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();
    }


    /**
     * Test login with valid credentials
     */
    public function testLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/user/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertAuthenticated();
    }

    /**
     * Test login with invalid credentials
     */
    public function testLoginWithInvalidCredentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/user/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertGuest();
    }

    /**
     * Test login requires email
     */
    public function testLoginRequiresEmail()
    {
        $response = $this->postJson('/user/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test login requires password
     */
    public function testLoginRequiresPassword()
    {
        $response = $this->postJson('/user/login', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test register with valid data
     */
    public function testRegisterWithValidData()
    {
        $response = $this->postJson('/user/register', [
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
            'email' => 'newuser@example.com',
            'name' => 'New User',
        ]);
    }

    /**
     * Test register requires name
     */
    public function testRegisterRequiresName()
    {
        $response = $this->postJson('/user/register', [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test register requires email
     */
    public function testRegisterRequiresEmail()
    {
        $response = $this->postJson('/user/register', [
            'name' => 'New User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test register requires unique email
     */
    public function testRegisterRequiresUniqueEmail()
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $response = $this->postJson('/user/register', [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test register requires password
     */
    public function testRegisterRequiresPassword()
    {
        $response = $this->postJson('/user/register', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test forgot password with valid email
     */
    public function testForgotPasswordWithValidEmail()
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/user/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test forgot password with invalid email
     */
    public function testForgotPasswordWithInvalidEmail()
    {
        $response = $this->postJson('/user/forgot-password', [
            'email' => 'nonexistent@example.com',
        ]);

        // Should still return success to prevent email scanning
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);
    }

    /**
     * Test reset password with valid token
     */
    public function testResetPasswordWithValidToken()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->postJson("/user/reset-password/{$user->email}/{$token}", [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        // Verify password was changed
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /**
     * Test reset password with invalid token
     */
    public function testResetPasswordWithInvalidToken()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->postJson("/user/reset-password/{$user->email}/invalid-token", [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test email verification with valid link
     */
    public function testEmailVerificationWithValidLink()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => null,
        ]);

        $hash = sha1($user->getEmailForVerification());

        $response = $this->getJson("/user/verification/{$user->id}/{$hash}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    /**
     * Test email verification with invalid hash
     */
    public function testEmailVerificationWithInvalidHash()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'email_verified_at' => null,
        ]);

        $response = $this->getJson("/user/verification/{$user->id}/invalid-hash");

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertNull($user->fresh()->email_verified_at);
    }

    /**
     * Test logout
     */
    public function testLogout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/user/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }
}
