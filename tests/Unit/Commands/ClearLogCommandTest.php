<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Commands;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Commands\ClearLogCommand;
use Juzaweb\Modules\Core\Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;

class ClearLogCommandTest extends TestCase
{
    public function test_clear_log_command_removes_logs()
    {
        // Register command
        $this->app[Kernel::class]->registerCommand(new ClearLogCommand());

        // Setup log directory
        $logPath = storage_path('logs');
        if (!File::exists($logPath)) {
            File::makeDirectory($logPath, 0755, true);
        }

        // Create dummy logs
        File::put($logPath . '/laravel.log', 'dummy log content');
        File::put($logPath . '/worker.log', 'dummy worker content');

        $this->assertFileExists($logPath . '/laravel.log');
        $this->assertFileExists($logPath . '/worker.log');

        // Run command
        $this->artisan('log:clear')
            ->assertExitCode(0)
            ->expectsOutput('Laravel logs have been cleared successfully.');

        // Assert files are gone
        $this->assertFileDoesNotExist($logPath . '/laravel.log');
        $this->assertFileDoesNotExist($logPath . '/worker.log');
    }

    public function test_clear_log_command_fails_if_directory_missing()
    {
         // Register command
        $this->app[Kernel::class]->registerCommand(new ClearLogCommand());

        // Remove log directory
        $logPath = storage_path('logs');
        if (File::exists($logPath)) {
            File::deleteDirectory($logPath);
        }

        // Run command
        $this->artisan('log:clear')
            ->assertExitCode(1)
            ->expectsOutput('Log directory does not exist.');
    }
}
