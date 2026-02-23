<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Models\Media;
use Juzaweb\Modules\Core\Tests\TestCase;

class SendMediaToCloudCommandTest extends TestCase
{
    public function test_command_fails_if_cloud_not_configured()
    {
        Config::set('filesystems.disks.cloud', null);

        $this->artisan('media:send-to-cloud')
            ->assertExitCode(1);
    }

    public function test_command_moves_media_to_cloud()
    {
        Storage::fake('public');
        Storage::fake('cloud');

        Config::set('filesystems.disks.cloud', [
            'driver' => 'local',
            'root' => storage_path('app/cloud'),
        ]);

        // Create a media record manually since factory might be missing
        $media = new Media();
        $media->forceFill([
            'name' => 'test.jpg',
            'path' => 'test.jpg',
            'disk' => 'public',
            'mime_type' => 'image/jpeg',
            'type' => 'file',
            'in_cloud' => false,
            'extension' => 'jpg',
            'size' => 1024,
        ]);
        $media->save();

        // Create the file in public disk
        Storage::disk('public')->put('test.jpg', 'content');

        $this->artisan('media:send-to-cloud')
            ->assertExitCode(0);

        // Verify file is in cloud
        Storage::disk('cloud')->assertExists('test.jpg');

        // Verify DB update
        $media->refresh();
        $this->assertTrue($media->in_cloud);
        $this->assertEquals('public', $media->disk);
    }
}
