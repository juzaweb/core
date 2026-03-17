<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\FileManager\MediaUploader;
use Juzaweb\Modules\Core\Tests\TestCase;

class UploaderTest extends TestCase
{
    public function test_upload()
    {
        $file = UploadedFile::fake()->image('file-one.jpg');

        $upload = MediaUploader::make($file)->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function test_upload_with_custom_name()
    {
        $file = UploadedFile::fake()->image('file-two.jpg');

        $upload = MediaUploader::make($file)->name('custom-name.jpg')->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function test_upload_by_url()
    {
        $url = 'https://placehold.co/150';

        $upload = MediaUploader::make($url)->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function test_upload_by_url_with_custom_name()
    {
        $url = 'https://placehold.co/150';

        $upload = MediaUploader::make($url)->name('custom-name.jpg')->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function test_upload_with_custom_disk()
    {
        $file = UploadedFile::fake()->image('file-three.jpg');

        $upload = MediaUploader::make($file)->disk('private')->upload();

        $this->assertTrue(Storage::disk('private')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }
}
