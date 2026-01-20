<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Translations\Models\Language;
use Juzaweb\Modules\Core\Translations\Models\LanguageLine;

class TranslationControllerTest extends TestCase
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

        // Create or update test languages
        Language::updateOrCreate(['code' => 'en'], ['name' => 'English']);
        Language::updateOrCreate(['code' => 'vi'], ['name' => 'Vietnamese']);
    }

    public function testIndex()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->get("/{$adminPrefix}/languages/en/translations");

        $response->assertStatus(200);
        $response->assertViewIs('core::admin.translation.index');
        $response->assertViewHas('title');
        $response->assertViewHas('locale', 'en');
    }

    public function testGetData()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->getJson("/{$adminPrefix}/languages/en/translations/get-data");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'recordsTotal',
            'recordsFiltered',
        ]);
    }

    public function testUpdate()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $payload = [
            'group' => 'core',
            'namespace' => 'core',
            'key' => 'test_key',
            'value' => 'Test Value',
        ];

        $response = $this->putJson("/{$adminPrefix}/languages/en/translations", $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('language_lines', [
            'group' => 'core',
            'namespace' => 'core',
            'key' => 'test_key',
        ]);

        $line = LanguageLine::where('key', 'test_key')
            ->where('group', 'core')
            ->where('namespace', 'core')
            ->first();

        $this->assertNotNull($line);
        $this->assertEquals('Test Value', $line->text['en'] ?? null);
    }
}
