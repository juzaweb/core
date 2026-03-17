<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Juzaweb\Modules\Core\Facades\Module;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\FileRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class ModuleFacadeTest extends TestCase
{
    public function test_module_facade_resolves_to_repository_interface()
    {
        $this->assertInstanceOf(
            RepositoryInterface::class,
            Module::getFacadeRoot()
        );
    }

    public function test_module_facade_resolves_to_file_repository()
    {
        $this->assertInstanceOf(
            FileRepository::class,
            Module::getFacadeRoot()
        );
    }

    public function test_module_all_returns_array()
    {
        $modules = Module::all();
        $this->assertIsArray($modules);
    }

    public function test_module_get_path()
    {
        $path = Module::getPath();
        $this->assertNotEmpty($path);
        $this->assertIsString($path);
    }
}
