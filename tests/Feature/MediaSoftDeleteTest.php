<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;
use Juzaweb\Modules\Core\Models\Media;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class MediaSoftDeleteTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->user);
    }

    public function testSoftDelete()
    {
        Storage::fake('public');

        // Create a dummy file
        Storage::disk('public')->put('test.txt', 'content');

        $media = Media::create([
            'name' => 'File to delete',
            'type' => MediaType::FILE,
            'mime_type' => 'text/plain',
            'path' => 'test.txt',
            'extension' => 'txt',
            'disk' => 'public',
        ]);

        // Assert file exists
        Storage::disk('public')->assertExists('test.txt');

        // Perform Soft Delete
        $media->delete();

        // Assert record exists but is soft deleted
        $this->assertSoftDeleted('media', ['id' => $media->id]);

        // Assert file STILL exists
        Storage::disk('public')->assertExists('test.txt');
    }

    public function testForceDelete()
    {
        Storage::fake('public');

        // Create a dummy file
        Storage::disk('public')->put('test_force.txt', 'content');

        $media = Media::create([
            'name' => 'File to force delete',
            'type' => MediaType::FILE,
            'mime_type' => 'text/plain',
            'path' => 'test_force.txt',
            'extension' => 'txt',
            'disk' => 'public',
        ]);

        // Perform Force Delete
        $media->forceDelete();

        // Assert record is gone
        $this->assertDatabaseMissing('media', ['id' => $media->id]);

        // Assert file is GONE
        Storage::disk('public')->assertMissing('test_force.txt');
    }
}
