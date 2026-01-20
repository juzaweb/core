<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Translations\Models\Language;

class LanguageControllerTest extends TestCase
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

        // Set default language
        setting()->set('language', 'en');
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        // Set translatable config
        $app['config']->set('translatable.fallback_locale', 'en');
        $app['config']->set('locales', [
            'en' => ['name' => 'English', 'regional' => 'en_US'],
            'vi' => ['name' => 'Vietnamese', 'regional' => 'vi_VN'],
            'fr' => [' name' => 'French', 'regional' => 'fr_FR'],
            'de' => ['name' => 'German', 'regional' => 'de_DE'],
        ]);
    }

    /**
     * Test language store with valid data
     */
    public function testStoreLanguageWithValidData()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/languages", [
            'code' => 'fr',
            'name' => 'French',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('languages', [
            'code' => 'fr',
            'name' => 'French',
        ]);
    }

    /**
     * Test language store updates existing language
     */
    public function testStoreLanguageUpdatesExisting()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/languages", [
            'code' => 'en',
            'name' => 'English Updated',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('languages', [
            'code' => 'en',
            'name' => 'English Updated',
        ]);
    }

    /**
     * Test language store requires code
     */
    public function testStoreLanguageRequiresCode()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/languages", [
            'name' => 'French',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    /**
     * Test language store requires name
     */
    public function testStoreLanguageRequiresName()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/languages", [
            'code' => 'fr',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Test language store validates code against locales config
     */
    public function testStoreLanguageValidatesCodeAgainstConfig()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/languages", [
            'code' => 'invalid_code',
            'name' => 'Invalid Language',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['code']);
    }

    /**
     * Test delete language successfully
     */
    public function testDeleteLanguage()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $language = Language::create([
            'code' => 'fr',
            'name' => 'French',
        ]);

        $response = $this->deleteJson("/{$adminPrefix}/languages/{$language->code}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('languages', [
            'code' => 'fr',
        ]);
    }

    /**
     * Test cannot delete fallback locale
     */
    public function testCannotDeleteFallbackLocale()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');
        $fallbackLocale = config('translatable.fallback_locale', 'en');

        // Ensure fallback locale exists
        Language::updateOrCreate(['code' => $fallbackLocale], ['name' => 'Fallback Language']);

        $response = $this->deleteJson("/{$adminPrefix}/languages/{$fallbackLocale}");

        $response->assertStatus(404);

        $this->assertDatabaseHas('languages', [
            'code' => $fallbackLocale,
        ]);
    }

    /**
     * Test cannot delete default language
     */
    public function testCannotDeleteDefaultLanguage()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        // Set 'en' as default
        setting()->set('language', 'en');

        $response = $this->deleteJson("/{$adminPrefix}/languages/en");

        $response->assertStatus(404);

        $this->assertDatabaseHas('languages', [
            'code' => 'en',
        ]);
    }

    /**
     * Test bulk delete languages
     */
    public function testBulkDeleteLanguages()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $language1 = Language::create(['code' => 'fr', 'name' => 'French']);
        $language2 = Language::create(['code' => 'de', 'name' => 'German']);

        $response = $this->postJson("/{$adminPrefix}/languages/bulk", [
            'ids' => ['fr', 'de'],
            'action' => 'delete',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('languages', ['code' => 'fr']);
        $this->assertDatabaseMissing('languages', ['code' => 'de']);
    }

    /**
     * Test bulk set default language
     */
    public function testBulkSetDefaultLanguage()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/languages/bulk", [
            'ids' => ['vi'],
            'action' => 'set-default',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        // Check database instead of using setting helper to avoid cache issues
        $this->assertDatabaseHas('settings', [
            'code' => 'language',
            'value' => 'vi',
        ]);
    }

    /**
     * Test bulk set default requires exactly one language
     */
    public function testBulkSetDefaultRequiresExactlyOne()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/languages/bulk", [
            'ids' => ['en', 'vi'],
            'action' => 'set-default',
        ]);

        // Response should be an error since we're selecting more than one
        $response->assertJson([
            'success' => false,
        ]);
    }

    /**
     * Test bulk cannot delete fallback locale
     */
    public function testBulkCannotDeleteFallbackLocale()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');
        $fallbackLocale = config('translatable.fallback_locale', 'en');

        Language::create(['code' => 'fr', 'name' => 'French']);

        $response = $this->postJson("/{$adminPrefix}/languages/bulk", [
            'ids' => [$fallbackLocale, 'fr'],
            'action' => 'delete',
        ]);

        $response->assertStatus(200);

        // Fallback locale should still exist
        $this->assertDatabaseHas('languages', [
            'code' => $fallbackLocale,
        ]);

        // But 'fr' should be deleted
        $this->assertDatabaseMissing('languages', [
            'code' => 'fr',
        ]);
    }

    /**
     * Test bulk cannot delete default language
     */
    public function testBulkCannotDeleteDefaultLanguage()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        setting()->set('language', 'en');
        Language::create(['code' => 'fr', 'name' => 'French']);

        $response = $this->postJson("/{$adminPrefix}/languages/bulk", [
            'ids' => ['en', 'fr'],
            'action' => 'delete',
        ]);

        $response->assertStatus(200);

        // Default language should still exist
        $this->assertDatabaseHas('languages', [
            'code' => 'en',
        ]);

        // But 'fr' should be deleted
        $this->assertDatabaseMissing('languages', [
            'code' => 'fr',
        ]);
    }
}
