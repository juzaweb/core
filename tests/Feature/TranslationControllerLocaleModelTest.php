<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Traits\LocaleModel;
use Juzaweb\Modules\Core\Translations\Contracts\CanBeTranslated;
use Juzaweb\Modules\Core\Translations\Contracts\Translator;

class TranslationControllerLocaleModelTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->artisan('migrate', ['--database' => 'testbench'])->run();

        $this->user = User::factory()->create([
            'is_super_admin' => 1,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($this->user);

        Schema::dropIfExists('test_posts');
        Schema::create('test_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('content')->nullable();
            $table->string('slug')->nullable();
            $table->string('locale')->default('en');
            $table->timestamps();
        });

        // Ensure translate_histories table exists
        if (!Schema::hasTable('translate_histories')) {
            Schema::create('translate_histories', function (Blueprint $table) {
                $table->id();
                $table->string('translateable_type');
                $table->unsignedBigInteger('translateable_id');
                $table->string('locale');
                $table->string('status');
                $table->text('error')->nullable();
                $table->string('new_model_id', 50)->nullable()->index();
                $table->string('new_model_type')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('test_posts');
        parent::tearDown();
    }

    public function testTranslateModelWithoutTranslationsTable()
    {
        config(['translator.enable' => true]);
        config()->set('locales', ['en' => ['name' => 'English'], 'vi' => ['name' => 'Vietnamese']]);

        $this->mock(Translator::class, function ($mock) {
            $mock->shouldReceive('translate')
                ->andReturn('Xin chào');
        });

        $post = TestPostLocaleModel::create(['title' => 'Hello', 'locale' => 'en']);

        $payload = [
            'model' => encrypt(TestPostLocaleModel::class),
            'ids' => [$post->id],
            'locale' => 'vi',
            'source' => 'en',
        ];

        $response = $this->postJson(route('admin.translations.translate-model'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure(['history_ids']);
    }
}

class TestPostLocaleModel extends Model implements CanBeTranslated
{
    use LocaleModel;

    protected $table = 'test_posts';
    protected $fillable = ['title', 'content', 'slug', 'locale'];

    protected $translatedAttributes = ['title'];
}
