<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\FileManager\MediaUploader;
use Juzaweb\Modules\Core\Tests\TestCase;

class SvgUploadTest extends TestCase
{
    public function test_svg_sanitization()
    {
        $svgContent = '<?xml version="1.0" standalone="no"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd"><svg version="1.1" baseProfile="full" xmlns="http://www.w3.org/2000/svg"><polygon id="triangle" points="0,0 0,50 50,0" fill="#009900" stroke="#004400"/><script type="text/javascript">alert(1);</script></svg>';

        $file = UploadedFile::fake()->createWithContent('malicious.svg', $svgContent);

        $upload = MediaUploader::make($file)->upload();

        $this->assertTrue(Storage::disk('public')->exists($upload->getPath()));

        $uploadedContent = Storage::disk('public')->get($upload->getPath());
        $this->assertStringNotContainsString('<script', $uploadedContent);

        $this->assertDatabaseHas('media', ['id' => $upload->id]);
    }
}
