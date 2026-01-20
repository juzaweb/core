<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;
use Juzaweb\Modules\Core\Mail\Test;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class SettingControllerTest extends TestCase
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
    }

    public function testSocialLogin()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->get("/{$adminPrefix}/settings/social-login");

        $response->assertStatus(200);
        $response->assertViewIs('core::admin.setting.social-login');
    }

    public function testEmailSetting()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->get("/{$adminPrefix}/settings/email");

        $response->assertStatus(200);
        $response->assertViewIs('core::admin.setting.email');
    }

    public function testSendTestEmail()
    {
        Mail::fake();

        $adminPrefix = config('core.admin_prefix', 'admin');
        $email = 'test@example.com';

        $response = $this->postJson("/{$adminPrefix}/settings/test-email", [
            'email' => $email,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        Mail::assertSent(Test::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    public function testSendTestEmailValidation()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/settings/test-email", [
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);

        $response = $this->postJson("/{$adminPrefix}/settings/test-email", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
