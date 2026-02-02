<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class ThemeManagerTest extends TestCase
{
    protected $user;
    protected $themePath;

    protected function setUp(): void
    {
        $this->themePath = dirname(__DIR__) . '/themes/test-theme';

        if (file_exists($this->themePath)) {
            exec('rm -rf ' . escapeshellarg($this->themePath));
        }

        mkdir($this->themePath, 0755, true);
        file_put_contents($this->themePath . '/theme.json', json_encode([
            "name" => "test-theme",
            "title" => "Test Theme",
            "version" => "1.0",
        ]));

        parent::setUp();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
        ]);
    }

    protected function tearDown(): void
    {
        if (File::exists($this->themePath)) {
            File::deleteDirectory($this->themePath);
        }
        parent::tearDown();
    }

    public function testDeleteTheme()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('admin.themes.delete'), [
            'theme' => 'test-theme',
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertStatus(200);
        $this->assertFalse(File::exists($this->themePath));
    }
}
