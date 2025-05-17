<?php

namespace Juzaweb\Core\Tests;

use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Juzaweb\FileManager\MediaUploader;

class UploaderTest extends TestCase
{
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
        $url = 'https://via.placeholder.com/150';

        $upload = MediaUploader::make($url)->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function testUploadByUrlWithCustomName()
    {
        $url = 'https://via.placeholder.com/150';

        $upload = MediaUploader::make($url)->name('custom-name.jpg')->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }

    public function testUploadWithCustomDisk()
    {
        $file = UploadedFile::fake()->image('file-three.jpg');

        $upload = MediaUploader::make($file)->disk('protected')->upload();

        $this->assertTrue(Storage::disk('protected')->exists($upload->getPath()));

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }
}
