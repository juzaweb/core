<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Modules\FileRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class FileRepositoryTest extends TestCase
{
    protected string $testModulePath;
    protected FileRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testModulePath = sys_get_temp_dir() . '/juzaweb_modules_test';

        if (File::exists($this->testModulePath)) {
            File::deleteDirectory($this->testModulePath);
        }

        File::makeDirectory($this->testModulePath);

        // Configure modules path
        $this->app['config']->set('modules.paths.modules', $this->testModulePath);
        $this->app['config']->set('modules.scan.enabled', false);
        $this->app['config']->set('modules.scan.paths', []);

        // Configure activator to use temp path
        $this->app['config']->set('modules.activator', 'file');
        $this->app['config']->set('modules.activators.file.statuses-file', $this->testModulePath . '/statuses.json');

        // Re-bind activator since we changed config
        $this->app->singleton(\Juzaweb\Modules\Core\Modules\Contracts\ActivatorInterface::class, function ($app) {
            $class = $app['config']->get('modules.activators.file.class');
            return new $class($app);
        });

        $this->repository = new FileRepository($this->app, $this->testModulePath);
    }

    protected function tearDown(): void
    {
        if (File::exists($this->testModulePath)) {
            File::deleteDirectory($this->testModulePath);
        }

        parent::tearDown();
    }

    protected function createDummyModule(string $name, int $priority = 0): void
    {
        $modulePath = $this->testModulePath . '/' . $name;
        File::makeDirectory($modulePath);

        $content = json_encode([
            'name' => $name,
            'alias' => strtolower($name),
            'description' => "Test module $name",
            'priority' => $priority,
        ]);

        File::put($modulePath . '/module.json', $content);
    }

    public function testAddLocationAndGetPaths()
    {
        $extraPath = $this->testModulePath . '/extra';
        $this->repository->addLocation($extraPath);

        $paths = $this->repository->getPaths();
        $this->assertContains($extraPath, $paths);
    }

    public function testGetScanPaths()
    {
        $scanPaths = $this->repository->getScanPaths();
        // The default path should be included and end with /*
        $expected = $this->testModulePath . '/*';
        $this->assertContains($expected, $scanPaths);
    }

    public function testScanAndAll()
    {
        $this->createDummyModule('Blog');
        $this->createDummyModule('Shop');

        $modules = $this->repository->scan();

        $this->assertCount(2, $modules);
        $this->assertArrayHasKey('Blog', $modules);
        $this->assertArrayHasKey('Shop', $modules);

        // test all() returns the same result (without cache)
        $allModules = $this->repository->all();
        $this->assertCount(2, $allModules);
    }

    public function testToCollection()
    {
        $this->createDummyModule('Blog');
        $this->createDummyModule('Shop');

        $collection = $this->repository->toCollection();
        $this->assertInstanceOf(\Juzaweb\Modules\Core\Modules\Support\Collection::class, $collection);
        $this->assertCount(2, $collection);
    }

    public function testCollections()
    {
        $this->createDummyModule('Blog'); // Enabled by default/stub
        $this->createDummyModule('Shop');

        // Ensure both enabled
        $this->repository->enable('Blog');
        $this->repository->enable('Shop');

        $collection = $this->repository->collections(1); // Enabled
        $this->assertCount(2, $collection);

        $this->repository->disable('Shop');
        $collectionEnabled = $this->repository->collections(1);
        $this->assertCount(1, $collectionEnabled);
        $this->assertEquals('Blog', $collectionEnabled->first()->getName());
    }

    public function testCount()
    {
        $this->createDummyModule('Blog');
        $this->assertEquals(1, $this->repository->count());

        $this->createDummyModule('Shop');
        $this->assertEquals(2, $this->repository->count());
    }

    public function testFind()
    {
        $this->createDummyModule('Blog');

        $module = $this->repository->find('Blog');
        $this->assertNotNull($module);
        $this->assertEquals('Blog', $module->getName());

        $moduleLower = $this->repository->find('blog');
        $this->assertNotNull($moduleLower);
        $this->assertEquals('Blog', $moduleLower->getName());

        $this->assertNull($this->repository->find('NonExistent'));
    }

    public function testFindOrFail()
    {
        $this->createDummyModule('Blog');

        $module = $this->repository->findOrFail('Blog');
        $this->assertEquals('Blog', $module->getName());

        $this->expectException(\Juzaweb\Modules\Core\Modules\Exceptions\ModuleNotFoundException::class);
        $this->repository->findOrFail('NonExistent');
    }

    public function testHas()
    {
        $this->createDummyModule('Blog');
        $this->assertTrue($this->repository->has('Blog'));
        $this->assertFalse($this->repository->has('Shop'));
    }

    public function testEnableDisableAndStatus()
    {
        $this->createDummyModule('Blog');

        // By default, modules are disabled or enabled depending on stubs.
        // Assuming default behavior, let's explicitly enable/disable.

        // Enable
        $this->repository->enable('Blog');
        $this->assertTrue($this->repository->isEnabled('Blog'));
        $this->assertFalse($this->repository->isDisabled('Blog'));

        // Disable
        $this->repository->disable('Blog');
        $this->assertFalse($this->repository->isEnabled('Blog'));
        $this->assertTrue($this->repository->isDisabled('Blog'));
    }

    public function testGetByStatus()
    {
        $this->createDummyModule('EnabledMod');
        $this->createDummyModule('DisabledMod');

        $this->repository->enable('EnabledMod');
        $this->repository->disable('DisabledMod');

        $enabled = $this->repository->allEnabled();
        $this->assertArrayHasKey('EnabledMod', $enabled);
        $this->assertArrayNotHasKey('DisabledMod', $enabled);

        $disabled = $this->repository->allDisabled();
        $this->assertArrayHasKey('DisabledMod', $disabled);
        $this->assertArrayNotHasKey('EnabledMod', $disabled);
    }

    public function testGetOrdered()
    {
        $this->createDummyModule('LowPriority', 1);
        $this->createDummyModule('HighPriority', 10);

        $this->repository->enable('LowPriority');
        $this->repository->enable('HighPriority');

        // ASC
        $orderedAsc = $this->repository->getOrdered('asc');
        $keysAsc = array_keys($orderedAsc);
        // Expect LowPriority (1) then HighPriority (10)
        // Note: getOrdered implementation:
        // if ($direction === 'desc') { return $a > $b ? 1 : -1; } (Wait, standard compare is $a <=> $b)
        // FileRepository:
        /*
            if ($direction === 'desc') {
                return $a->get('priority') < $b->get('priority') ? 1 : -1;
            }
            return $a->get('priority') > $b->get('priority') ? 1 : -1;
        */
        // If direction is 'asc' (default/else), it returns $a > $b ? 1 : -1.
        // If a=10, b=1. 10 > 1 is true -> 1. So a comes after b.
        // So 'asc' implementation in FileRepository seems to sort 1...10.

        // Let's verify expectations:
        // 'asc' -> 1 then 10.
        $this->assertEquals('LowPriority', $keysAsc[0]);
        $this->assertEquals('HighPriority', $keysAsc[1]);

        // DESC
        $orderedDesc = $this->repository->getOrdered('desc');
        $keysDesc = array_keys($orderedDesc);
        $this->assertEquals('HighPriority', $keysDesc[0]);
        $this->assertEquals('LowPriority', $keysDesc[1]);
    }

    public function testDelete()
    {
        $this->createDummyModule('ToDelete');
        $this->assertTrue($this->repository->has('ToDelete'));

        $this->repository->delete('ToDelete');

        // After delete, scan again
        $this->assertFalse($this->repository->has('ToDelete'));
        $this->assertFalse(File::exists($this->testModulePath . '/ToDelete'));
    }

    public function testConfig()
    {
        $this->app['config']->set('modules.some.key', 'value');
        $this->assertEquals('value', $this->repository->config('some.key'));
    }

    public function testGetModulePath()
    {
        $this->createDummyModule('Blog');
        $path = $this->repository->getModulePath('Blog');

        $this->assertEquals(
            str_replace('\\', '/', $this->testModulePath . '/Blog/'),
            str_replace('\\', '/', $path)
        );

        // For non-existent module, it returns predicted path
        $pathNew = $this->repository->getModulePath('NewMod');
        $this->assertStringContainsString('new-mod', $pathNew);
    }

    public function testAssetPath()
    {
        $path = $this->repository->assetPath('Blog');
        // config('modules.paths.assets') defaults to public_path('modules')
        // In testbench, public_path is usually mocked or points to testbench/public
        $expected = $this->repository->config('paths.assets') . '/Blog';
        $this->assertEquals($expected, $path);
    }

    public function testAsset()
    {
        // Setup config so we know what public_path and assets path are
        // In this test env, public_path is typically workbench/public or similar.
        // FileRepository logic: $baseUrl = str_replace(public_path() . DIRECTORY_SEPARATOR, '', $this->getAssetsPath());

        // Let's force assets path to be inside public path for this calculation to work as expected in the class
        $this->app['config']->set('modules.paths.assets', public_path('modules'));

        $assetUrl = $this->repository->asset('Blog:css/style.css');

        // The asset helper usually returns full URL http://localhost/modules/Blog/css/style.css
        // FileRepository replaces http:// or https:// with //

        $this->assertStringContainsString('//', $assetUrl);
        $this->assertStringContainsString('modules/Blog/css/style.css', $assetUrl);

        $this->expectException(\Juzaweb\Modules\Core\Modules\Exceptions\InvalidAssetPath::class);
        $this->repository->asset('invalid-asset-string');
    }

    public function testUsedModules()
    {
        $this->createDummyModule('Blog');

        $this->repository->setUsed('Blog');
        $this->assertEquals('Blog', $this->repository->getUsedNow());

        $this->repository->forgetUsed();

        $this->expectException(\Juzaweb\Modules\Core\Modules\Exceptions\ModuleNotFoundException::class);
        $this->repository->getUsedNow();
    }

    public function testStubPath()
    {
        $this->repository->setStubPath('/custom/stubs');
        $this->assertEquals('/custom/stubs', $this->repository->getStubPath());

        // Reset to null to test config fallback
        $this->repository->setStubPath(null);
        $this->app['config']->set('modules.stubs.enabled', true);
        $this->app['config']->set('modules.stubs.path', '/config/stubs');

        // Wait, setStubPath sets property, getStubPath checks property then config.
        // Property was set to null? "if ($this->stubPath !== null)"
        // Since we can't easily unset the property via public API (setStubPath assigns value), passing null works if implementation allows.
        // setStubPath implementation: $this->stubPath = $stubPath;
        // So passing null sets it to null.

        $this->assertEquals('/config/stubs', $this->repository->getStubPath());
    }
}
