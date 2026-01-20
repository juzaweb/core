<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;
use Juzaweb\Modules\Core\Models\Media;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;

class MediaControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->defineDatabaseMigrations();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->user);
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);
    }

    public function testIndex()
    {
        $response = $this->get(route('admin.media.index'));

        $response->assertStatus(200);
        $response->assertViewIs('core::admin.media.index');
    }

    public function testAddFolder()
    {
        $response = $this->postJson(route('admin.media.folders.store'), [
            'name' => 'Test Folder',
            'folder_id' => null,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('media', [
            'name' => 'Test Folder',
            'type' => MediaType::DIRECTORY,
        ]);
    }

    public function testUpload()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->postJson(route('media.upload', ['disk' => 'public']), [
            'upload' => $file,
            'working_dir' => null,
        ]);

        $response->assertStatus(200);

        $this->assertEquals('"OK"', $response->getContent());

        // Check if file is stored
        // The path in storage is generated, so exact path checking might be tricky without knowing logic.
        // But we can check database.

        $this->assertDatabaseHas('media', [
            'name' => 'test_image.jpg',
            'mime_type' => 'image/jpeg',
            'type' => MediaType::FILE,
        ]);
    }

    public function testDelete()
    {
        $media = Media::create([
            'name' => 'File to delete',
            'type' => MediaType::FILE,
            'mime_type' => 'text/plain',
            'path' => 'path/to/file.txt',
            'extension' => 'txt',
        ]);

        $response = $this->deleteJson(route('admin.media.delete', [$media->id]));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('media', [
            'id' => $media->id,
        ]);
    }
}
