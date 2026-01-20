<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\Models\Pages\Page;
use Juzaweb\Modules\Core\Models\User;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Translations\Models\Language;

class PageControllerTest extends TestCase
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

        // Create test language
        Language::updateOrCreate(['code' => 'en'], ['name' => 'English']);
    }

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        // Set translatable config
        $app['config']->set('translatable.fallback_locale', 'en');
        $app['config']->set('locales', [
            'en' => ['name' => 'English', 'regional' => 'en_US'],
        ]);
    }

    /**
     * Test store page with valid data
     */
    public function testStorePageWithValidData()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/pages", [
            'locale' => 'en',
            'status' => 'published',
            'title' => 'Test Page',
            'content' => 'This is test content',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('page_translations', [
            'title' => 'Test Page',
            'locale' => 'en',
        ]);
    }

    /**
     * Test store page requires title
     */
    public function testStorePageRequiresTitle()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/pages", [
            'locale' => 'en',
            'status' => 'published',
            'content' => 'This is test content',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test store page requires locale
     */
    public function testStorePageRequiresLocale()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/pages", [
            'status' => 'published',
            'title' => 'Test Page',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['locale']);
    }

    /**
     * Test store page requires status
     */
    public function testStorePageRequiresStatus()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->postJson("/{$adminPrefix}/pages", [
            'locale' => 'en',
            'title' => 'Test Page',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    /**
     * Test update page with valid data
     */
    public function testUpdatePageWithValidData()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $page = Page::create([
            'status' => PageStatus::DRAFT,
        ]);

        $page->translations()->create([
            'locale' => 'en',
            'title' => 'Original Title',
            'content' => 'Original content',
        ]);

        $response = $this->putJson("/{$adminPrefix}/pages/{$page->id}", [
            'locale' => 'en',
            'status' => 'published',
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('page_translations', [
            'page_id' => $page->id,
            'title' => 'Updated Title',
            'locale' => 'en',
        ]);

        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'status' => PageStatus::PUBLISHED->value,
        ]);
    }

    /**
     * Test delete page
     */
    public function testDeletePage()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $page = Page::create(['status' => PageStatus::DRAFT]);

        $response = $this->deleteJson("/{$adminPrefix}/pages/{$page->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('pages', [
            'id' => $page->id,
        ]);
    }

    /**
     * Test delete non-existent page returns 404
     */
    public function testDeleteNonExistentPageReturns404()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $response = $this->deleteJson("/{$adminPrefix}/pages/non-existent-id");

        $response->assertStatus(404);
    }

    /**
     * Test bulk delete pages
     */
    public function testBulkDeletePages()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $page1 = Page::create(['status' => PageStatus::DRAFT]);
        $page2 = Page::create(['status' => PageStatus::DRAFT]);

        $response = $this->postJson("/{$adminPrefix}/pages/bulk", [
            'ids' => [$page1->id, $page2->id],
            'action' => 'delete',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseMissing('pages', ['id' => $page1->id]);
        $this->assertDatabaseMissing('pages', ['id' => $page2->id]);
    }

    /**
     * Test bulk publish pages
     */
    public function testBulkPublishPages()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $page1 = Page::create(['status' => PageStatus::DRAFT]);
        $page2 = Page::create(['status' => PageStatus::DRAFT]);

        $response = $this->postJson("/{$adminPrefix}/pages/bulk", [
            'ids' => [$page1->id, $page2->id],
            'action' => 'publish',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('pages', [
            'id' => $page1->id,
            'status' => PageStatus::PUBLISHED->value,
        ]);

        $this->assertDatabaseHas('pages', [
            'id' => $page2->id,
            'status' => PageStatus::PUBLISHED->value,
        ]);
    }

    /**
     * Test bulk draft pages
     */
    public function testBulkDraftPages()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $page1 = Page::create(['status' => PageStatus::PUBLISHED]);
        $page2 = Page::create(['status' => PageStatus::PUBLISHED]);

        $response = $this->postJson("/{$adminPrefix}/pages/bulk", [
            'ids' => [$page1->id, $page2->id],
            'action' => 'draft',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('pages', [
            'id' => $page1->id,
            'status' => PageStatus::DRAFT->value,
        ]);

        $this->assertDatabaseHas('pages', [
            'id' => $page2->id,
            'status' => PageStatus::DRAFT->value,
        ]);
    }

    /**
     * Test bulk with invalid action returns error
     */
    public function testBulkWithInvalidActionReturnsError()
    {
        $adminPrefix = config('core.admin_prefix', 'admin');

        $page = Page::create(['status' => PageStatus::DRAFT]);

        $response = $this->postJson("/{$adminPrefix}/pages/bulk", [
            'ids' => [$page->id],
            'action' => 'invalid_action',
        ]);

        $response->assertJson([
            'success' => false,
        ]);
    }
}
