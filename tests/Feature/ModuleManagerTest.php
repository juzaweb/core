<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;
use ZipArchive;

class ModuleManagerTest extends TestCase
{
    protected $modulePath;
    protected $user;

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $this->modulePath = sys_get_temp_dir() . '/juzaweb_modules_test';
        if (File::exists($this->modulePath)) {
            File::deleteDirectory($this->modulePath);
        }
        $app['config']->set('modules.paths.modules', $this->modulePath);
    }

    protected function setUp(): void
    {
        parent::setUp();

        File::ensureDirectoryExists($this->modulePath);

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
        ]);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->modulePath);
        parent::tearDown();
    }

    public function testInstallAndDeleteModule()
    {
        $this->actingAs($this->user);

        // 1. Create Zip
        $zipPath = sys_get_temp_dir() . '/test_module.zip';
        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addEmptyDir('TestModule');
        $zip->addFromString('TestModule/module.json', json_encode([
            'name' => 'TestModule',
            'alias' => 'testmodule',
            'version' => '1.0',
            'title' => 'Test Module'
        ]));
        $zip->close();

        // 2. Upload
        Storage::fake('tmp');
        Storage::disk('tmp')->put('test_module.zip', file_get_contents($zipPath));

        $response = $this->post(route('admin.modules.install-from-zip'), [
            'path' => 'test_module.zip',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert installed
        $this->assertTrue(File::exists($this->modulePath . '/TestModule/module.json'));

        // 3. Delete
        $response = $this->post(route('admin.modules.delete'), [
            'module' => 'TestModule',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Assert deleted
        $this->assertFalse(File::exists($this->modulePath . '/TestModule'));
    }
}
