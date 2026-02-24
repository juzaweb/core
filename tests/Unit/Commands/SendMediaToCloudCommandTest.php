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

        // dump(config('filesystems.disks.cloud'));

        // Manually set config to match what fake does, but ensure it is not empty
        // Storage::fake sets it, but maybe empty check fails?
        // Let's set it explicitly to something that matches the fake if we can,
        // OR just set a dummy config that allows Storage::build to work with the FAKE.

        // PROBLEM: cloud(true) calls Storage::build($config).
        // If we provide a config, Storage::build creates a NEW disk.
        // We want cloud(true) to return the SAME disk as Storage::disk('cloud').

        // If we set the config to use the 'faked' driver? No such driver usually.
        // Storage::fake replaces the driver in the resolved instance.

        // If we want cloud(true) to return the fake, we must bypass the "if ($write && $config)" block in cloud() helper?
        // But $write is true. $config is NOT empty (if we want to pass the check).

        // So cloud(true) WILL call Storage::build($config).

        // We need Storage::build($config) to return a disk that writes to the same place as the fake.

        // Storage::fake('cloud') creates a Local adapter at storage_path('framework/testing/disks/cloud').
        // So we should configure the config to point there!

        $fakeRoot = storage_path('framework/testing/disks/cloud');
        Config::set('filesystems.disks.cloud', [
            'driver' => 'local',
            'root' => $fakeRoot,
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
