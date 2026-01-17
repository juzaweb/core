<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\FileManager\MediaUploader;
use Juzaweb\Modules\Core\Tests\TestCase;


class UploaderTest extends TestCase
{
    use RefreshDatabase;

    public function testUpload()
    {
        $file = UploadedFile::fake()->image('file-one.jpg');

        $upload = MediaUploader::make($file)->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function testUploadWithCustomName()
    {
        $file = UploadedFile::fake()->image('file-two.jpg');

        $upload = MediaUploader::make($file)->name('custom-name.jpg')->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function testUploadByUrl()
    {
        $url = 'https://placehold.co/150';

        $upload = MediaUploader::make($url)->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function testUploadByUrlWithCustomName()
    {
        $url = 'https://placehold.co/150';

        $upload = MediaUploader::make($url)->name('custom-name.jpg')->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function testUploadWithCustomDisk()
    {
        $file = UploadedFile::fake()->image('file-three.jpg');

        $upload = MediaUploader::make($file)->disk('private')->upload();

        $this->assertTrue(Storage::disk('private')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }
}
