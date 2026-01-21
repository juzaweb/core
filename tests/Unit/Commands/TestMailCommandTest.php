<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Commands;

use Illuminate\Support\Facades\Mail;
use Juzaweb\Modules\Core\Commands\TestMailCommand;
use Juzaweb\Modules\Core\Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;
use Juzaweb\Modules\Core\Mail\Test;

class TestMailCommandTest extends TestCase
{
    public function test_mail_test_command_sends_email()
    {
        // Register command
        $this->app[Kernel::class]->registerCommand(new TestMailCommand());

        Mail::fake();

        $this->artisan('mail:test', ['--email' => 'test@example.com'])
            ->assertExitCode(0)
            ->expectsOutput('Mail sent successfully, check your inbox.');

        Mail::assertSent(Test::class, function ($mail) {
            return $mail->hasTo('test@example.com');
        });
    }

    public function test_mail_test_command_fails_without_email()
    {
        // Register command
        $this->app[Kernel::class]->registerCommand(new TestMailCommand());

        $this->artisan('mail:test')
             ->assertExitCode(1)
             ->expectsOutput('Email is required');
    }
}
