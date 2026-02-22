<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Translations\Contracts\CanBeTranslated;
use Juzaweb\Modules\Core\Traits\LocaleModel;
use Juzaweb\Modules\Core\Translations\Contracts\Translator;
use Juzaweb\Modules\Core\Translations\Jobs\ModelTranslateJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Queue;
use Juzaweb\Modules\Core\Translations\Models\TranslateHistory;
use Juzaweb\Modules\Core\Translations\Enums\TranslateHistoryStatus;
use Juzaweb\Modules\Core\Models\Media;
use Juzaweb\Modules\Core\FileManager\Traits\HasMedia;
use Juzaweb\Modules\Core\FileManager\Enums\MediaType;

class LocaleModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('test_posts');
        Schema::create('test_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->string('slug')->nullable();
            $table->string('locale')->default('en');
            $table->timestamps();
        });

        // Ensure translate_histories table exists (might be created by migrations in TestCase, but to be safe)
        if (!Schema::hasTable('translate_histories')) {
            Schema::create('translate_histories', function (Blueprint $table) {
                $table->id();
                $table->string('translateable_type');
                $table->unsignedBigInteger('translateable_id');
                $table->string('locale');
                $table->string('status');
                $table->text('error')->nullable();
                $table->timestamps();
            });
        }

        // Media tables
        if (!Schema::hasTable('media')) {
            Schema::create('media', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('disk', 20)->index()->default('public');
                $table->nullableUuidMorphs('uploaded_by');
                $table->string('name');
                $table->string('type', 5)->index()->default('file');
                $table->string('path', 190)->nullable();
                $table->string('mime_type', 100)->index()->nullable();
                $table->string('extension', 10)->index()->nullable();
                $table->string('image_size', 20)->nullable();
                $table->bigInteger('size')->default(0);
                $table->json('conversions')->nullable();
                $table->json('metadata')->nullable();
                $table->uuid('parent_id')->index()->nullable();
                $table->boolean('in_cloud')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('mediable')) {
            Schema::create('mediable', function (Blueprint $table) {
                $table->primary(['media_id', 'mediable_id', 'mediable_type', 'channel']);
                $table->uuid('media_id')->index();
                $table->string('mediable_id', 100);
                $table->string('mediable_type', 150);
                $table->string('channel', 50)->index();
                $table->timestamps();
            });
        }
    }

    public function test_model_translate_function()
    {
        Queue::fake();

        $post = TestPost::create(['title' => 'Hello', 'locale' => 'en']);

        $history = model_translate($post, 'en', 'vi');

        $this->assertInstanceOf(TranslateHistory::class, $history);
        $this->assertEquals('vi', $history->locale);
        $this->assertEquals(TranslateHistoryStatus::PENDING, $history->status);

        Queue::assertPushed(ModelTranslateJob::class);
    }

    public function test_translate_to_method()
    {
        $this->mock(Translator::class, function ($mock) {
            $mock->shouldReceive('translate')
                ->with('Hello', 'en', 'vi', false)
                ->andReturn('Xin chào');
        });

        $post = TestPost::create(['title' => 'Hello', 'locale' => 'en']);

        // We simulate the call without the job wrapper to test the trait logic directly
        $result = $post->translateTo('vi', 'en');

        $this->assertTrue($result);

        $this->assertDatabaseHas('test_posts', [
            'title' => 'Xin chào',
            'locale' => 'vi',
        ]);

        $this->assertDatabaseHas('test_posts', [
            'title' => 'Hello',
            'locale' => 'en',
        ]);
    }

    public function test_translate_to_replicates_media_channels()
    {
        $this->mock(Translator::class, function ($mock) {
            $mock->shouldReceive('translate')
                ->andReturn('Xin chào');
        });

        // Create a media
        $media = Media::create([
            'name' => 'test.jpg',
            'path' => 'test.jpg',
            'type' => MediaType::FILE,
            'mime_type' => 'image/jpeg',
            'extension' => 'jpg',
            'disk' => 'public',
        ]);

        $post = TestPostWithMedia::create(['title' => 'Hello', 'locale' => 'en']);

        // Attach media
        $post->attachMedia($media, 'thumbnail');

        // Translate
        $post->translateTo('vi', 'en');

        // Check new post
        $newPost = TestPostWithMedia::where('locale', 'vi')->first();
        $this->assertNotNull($newPost);

        // Assert media is attached
        $this->assertTrue($newPost->hasMedia('thumbnail'), 'Media was not replicated to the new translation');
        $this->assertEquals($media->id, $newPost->getFirstMedia('thumbnail')->id);
    }
}

class TestPost extends Model implements CanBeTranslated
{
    use LocaleModel;

    protected $table = 'test_posts';
    protected $fillable = ['title', 'content', 'slug', 'locale'];

    protected $translatedAttributes = ['title'];
}

class TestPostWithMedia extends Model implements CanBeTranslated
{
    use LocaleModel, HasMedia;

    protected $table = 'test_posts';
    protected $fillable = ['title', 'content', 'slug', 'locale'];

    protected $translatedAttributes = ['title'];

    public $mediaChannels = ['thumbnail'];
}
