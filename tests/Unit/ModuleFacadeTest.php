<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Juzaweb\Modules\Core\Facades\Module;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\FileRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class ModuleFacadeTest extends TestCase
{
    public function testModuleFacadeResolvesToRepositoryInterface()
    {
        $this->assertInstanceOf(
            RepositoryInterface::class,
            Module::getFacadeRoot()
        );
    }

    public function testModuleFacadeResolvesToFileRepository()
    {
        $this->assertInstanceOf(
            FileRepository::class,
            Module::getFacadeRoot()
        );
    }

    public function testModuleAllReturnsArray()
    {
        $modules = Module::all();
        $this->assertIsArray($modules);
    }

    public function testModuleGetPath()
    {
        $path = Module::getPath();
        $this->assertNotEmpty($path);
        $this->assertIsString($path);
    }
}
