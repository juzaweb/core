<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Commands;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Commands\CacheSizeCommand;
use Juzaweb\Modules\Core\Tests\TestCase;
use Illuminate\Contracts\Console\Kernel;

class CacheSizeCommandTest extends TestCase
{
    public function test_cache_size_command_calculates_correctly()
    {
        // Register command
        $this->app[Kernel::class]->registerCommand(new CacheSizeCommand());

        // Setup cache directory
        $cachePath = storage_path('framework/cache');
        if (!File::exists($cachePath)) {
            File::makeDirectory($cachePath, 0755, true);
        }

        // Clean up before test
        File::cleanDirectory($cachePath);

        // Create a dummy file of 1024 bytes (1 KB)
        $content = str_repeat('a', 1024);
        File::put($cachePath . '/test_cache_file', $content);

        // Run command
        $this->artisan('cache:size')
            ->assertExitCode(0)
            ->expectsOutputToContain('KB');

        // Clean up
        File::cleanDirectory($cachePath);
    }
}
