<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\Module;
use Mockery;

class ModuleUpdateCommandTest extends TestCase
{
    public function test_module_update_command_for_blog_module()
    {
        // Mock the module name as "Blog" (which corresponds to "juzaweb/blog" package)
        $moduleName = 'Blog';

        // Mock the Module class
        $module = Mockery::mock(Module::class);
        $module->shouldReceive('getName')->andReturn($moduleName);
        $module->shouldReceive('__toString')->andReturn($moduleName);

        // Mock the Module Repository
        $repository = Mockery::mock(RepositoryInterface::class);

        // The command will call findOrFail('Blog') to get the module instance
        $repository->shouldReceive('findOrFail')
            ->with($moduleName)
            ->andReturn($module);

        // The command calls update($module) inside the task closure.
        // It also calls update($name) (string) after the task.
        // So we expect 'update' to be called at least once.
        $repository->shouldReceive('update')
            ->with(Mockery::on(function($arg) use ($moduleName, $module) {
                // Accepts either the Module object or the module name string
                return $arg === $module || (string)$arg === $moduleName;
            }))
            ->atLeast()->once();

        // Bind the mock to the container
        $this->app->instance(RepositoryInterface::class, $repository);
        $this->app->instance('modules', $repository);

        // Run the module:update command for the "Blog" module
        $this->artisan('module:update', ['module' => $moduleName])
            ->assertExitCode(0);
    }
}
