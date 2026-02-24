<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Commands;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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

        // Define a real temporary path for the 'cloud' disk
        // We cannot use Storage::fake('cloud') because the helper cloud(true)
        // uses Storage::build() which creates a new disk instance from config,
        // bypassing the fake. We must ensure the config points to a path we can verify.
        $cloudRoot = storage_path('framework/testing/disks/cloud_test_' . uniqid());

        // Ensure clean state
        if (File::exists($cloudRoot)) {
            File::deleteDirectory($cloudRoot);
        }
        File::makeDirectory($cloudRoot, 0755, true);

        Config::set('filesystems.disks.cloud', [
            'driver' => 'local',
            'root' => $cloudRoot,
            'throw' => false,
        ]);

        try {
            // Create a media record
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

            // Verify file exists in the real cloud directory
            $this->assertFileExists($cloudRoot . '/test.jpg');

            // Verify DB update
            $media->refresh();
            $this->assertTrue($media->in_cloud);
            $this->assertEquals('public', $media->disk);
        } finally {
            // Cleanup
            if (File::exists($cloudRoot)) {
                File::deleteDirectory($cloudRoot);
            }
        }
    }
}
