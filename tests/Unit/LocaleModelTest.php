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

class LocaleModelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

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
}

class TestPost extends Model implements CanBeTranslated
{
    use LocaleModel;

    protected $table = 'test_posts';
    protected $fillable = ['title', 'content', 'slug', 'locale'];

    protected $translatedAttributes = ['title'];
}
