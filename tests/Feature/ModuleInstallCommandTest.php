<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Tests\TestCase;
use Mockery;

class ModuleInstallCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Backup existing composer.phar if it exists to avoid overwriting or using real composer
        if (file_exists(base_path('composer.phar'))) {
            rename(base_path('composer.phar'), base_path('composer.phar.bak'));
        }

        // Create a mock composer.phar in the base path
        $composerPath = base_path('composer.phar');
        file_put_contents($composerPath, '#!/usr/bin/env php' . PHP_EOL . '<?php echo "Mock Composer Run"; exit(0);');
        chmod($composerPath, 0755);

        // Register a dummy module:publish command as it seems to be missing in the environment
        $publishCommand = new class extends Command {
            protected $signature = 'module:publish {module}';
            public function handle() { return 0; }
        };
        $this->app[Kernel::class]->registerCommand($publishCommand);
    }

    protected function tearDown(): void
    {
        $composerPath = base_path('composer.phar');
        if (file_exists($composerPath)) {
            unlink($composerPath);
        }

        // Restore backup if it exists
        if (file_exists(base_path('composer.phar.bak'))) {
            rename(base_path('composer.phar.bak'), base_path('composer.phar'));
        }

        parent::tearDown();
    }

    public function test_module_install_command_success()
    {
        $moduleName = 'juzaweb/blog';
        $shortName = 'Blog';

        // Mock the Module class
        $module = Mockery::mock(\Juzaweb\Modules\Core\Modules\Module::class);
        $module->shouldReceive('getName')->andReturn($shortName);
        $module->shouldReceive('__toString')->andReturn($shortName);
        // The Updater process will try to read composer requirements from the module
        $module->shouldReceive('getComposerAttr')
            ->with('require', [])
            ->andReturn([]);

        // Mock the Module Repository
        $repository = Mockery::mock(RepositoryInterface::class);

        // Setup expectation: getModulePath should be called with 'Blog'
        $repository->shouldReceive('getModulePath')
            ->with($shortName)
            ->andReturn('/tmp/modules/' . $shortName);

        // ModuleUpdateCommand calls findOrFail('Blog')
        // Also Updater calls findOrFail inside update()
        $repository->shouldReceive('findOrFail')
            ->with($shortName)
            ->andReturn($module);

        // ModuleUpdateCommand calls update.
        $repository->shouldReceive('update')
            ->with(Mockery::on(function($arg) use ($shortName) {
                return (string)$arg === $shortName;
            }));

        // Bind the mock to the container
        $this->app->instance(RepositoryInterface::class, $repository);
        // Also bind to 'modules' alias as used in the command ($this->laravel['modules'])
        $this->app->instance('modules', $repository);

        // Run the module:install command
        $this->artisan('module:install', ['name' => $moduleName])
            ->assertExitCode(0);
    }
}
