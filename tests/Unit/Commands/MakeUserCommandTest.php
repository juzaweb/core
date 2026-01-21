<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Commands;

use Illuminate\Support\Facades\Hash;
use Juzaweb\Modules\Core\Commands\MakeUserCommand;
use Juzaweb\Modules\Core\Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;
use Juzaweb\Modules\Core\Models\User;

class MakeUserCommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // Register command
        $this->app[Kernel::class]->registerCommand(new MakeUserCommand());
    }

    public function test_make_user_command_with_options()
    {
        $this->artisan('user:make', [
            '--name' => 'Test User',
            '--email' => 'test@example.com',
            '--pass' => 'password123',
            '--super-admin' => true,
        ])
        ->assertExitCode(0)
        ->expectsOutput('User created successfully user Test User');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'is_super_admin' => 1,
        ]);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    public function test_make_user_command_interactive()
    {
        $this->artisan('user:make')
            ->expectsQuestion('Full Name?', 'Interactive User')
            ->expectsQuestion('Email?', 'interactive@example.com')
            ->expectsQuestion('Password?', 'secret123')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'email' => 'interactive@example.com',
            'name' => 'Interactive User',
        ]);
    }

    public function test_make_user_validation_fails()
    {
        // Invalid email
        $this->artisan('user:make', [
            '--name' => 'Test',
            '--email' => 'not-an-email',
            '--pass' => '123456',
        ])
        ->assertExitCode(1);
    }
}
