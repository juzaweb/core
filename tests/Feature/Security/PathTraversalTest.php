<?php

namespace Juzaweb\Modules\Core\Tests\Feature\Security;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\FileManager\Http\Controllers\BrowserController;
use Juzaweb\Modules\Core\FileManager\Http\Controllers\DownloadController;
use Juzaweb\Modules\Core\Tests\TestCase;

class PathTraversalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->app['config']->set('juzaweb.filemanager.disk', 'public');

        // Create a file at the root of the 'public' disk
        Storage::disk('public')->put('safe.txt', 'safe data');

        // Create a sensitive file outside the 'public' disk root
        $publicPath = Storage::disk('public')->path('');
        $outsidePath = dirname($publicPath, 2).'/sensitive.txt';
        @file_put_contents($outsidePath, 'secret data');

        Route::get('_test/show-file/{path}', [BrowserController::class, 'showFile'])
            ->where('path', '.*');
        Route::get('_test/download-file', [DownloadController::class, 'getDownload']);
    }

    public function test_show_file_prevents_path_traversal()
    {
        // Standard traversal
        $this->get('_test/show-file/'.urlencode('../../sensitive.txt'))->assertStatus(404);

        // Nested sequences traversal bypasses (e.g. ....//)
        $this->get('_test/show-file/'.urlencode('....//....//sensitive.txt'))->assertStatus(404);

        // Path ending with ..
        $this->get('_test/show-file/'.urlencode('subdir/..'))->assertStatus(404);
    }

    public function test_download_file_prevents_path_traversal()
    {
        // Standard traversal
        $this->get('_test/download-file?file='.urlencode('../../sensitive.txt'))->assertStatus(404);

        // Nested sequences traversal bypasses
        $this->get('_test/download-file?file='.urlencode('....//....//sensitive.txt'))->assertStatus(404);

        // Path ending with ..
        $this->get('_test/download-file?file='.urlencode('subdir/..'))->assertStatus(404);
    }

    public function test_show_file_success()
    {
        $response = $this->get('_test/show-file/safe.txt');
        $response->assertStatus(200);
        $this->assertEquals('safe data', $response->getContent());
    }
}
