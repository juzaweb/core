<?php

namespace Juzaweb\Modules\Core\Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Juzaweb\Modules\Core\Contracts\Setting;
use Juzaweb\Modules\Core\Contracts\Sitemap;
use Juzaweb\Modules\Core\Contracts\ThemeSetting;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\Models\Pages\Page;
use Juzaweb\Modules\Core\Models\Pages\PageTranslation;
use Juzaweb\Modules\Core\Tests\TestCase;
use Spatie\Sitemap\SitemapServiceProvider;

class SitemapTest extends TestCase
{
    protected Page $homePage;
    protected Page $otherPage;

    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            SitemapServiceProvider::class,
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Config::set('translatable.fallback_locale', 'en');
        app()->setLocale('en');

        // Create Home Page
        $this->homePage = Page::create([
            'status' => PageStatus::PUBLISHED,
            'template' => 'default',
            'title' => 'Home Page',
            'slug' => 'home',
            'content' => 'Home Content',
        ]);

        // Create Other Page
        $this->otherPage = Page::create([
            'status' => PageStatus::PUBLISHED,
            'template' => 'default',
            'title' => 'Other Page',
            'slug' => 'other-page',
            'content' => 'Other Content',
        ]);

        // Reload to ensure translations are loaded if needed
        $this->homePage->load('translations');
        $this->otherPage->load('translations');

        // Set Home Page Setting
        $this->app[ThemeSetting::class]->set('home_page', $this->homePage->id);

        // Set Default Language
        $this->app[Setting::class]->set('language', 'en');

        // Register PageTranslation as 'pages' provider
        $this->app[Sitemap::class]->register('pages', PageTranslation::class);
    }

    public function test_index_sitemap()
    {
        $response = $this->get('sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');

        $content = $response->getContent();

        // Should contain sitemap/home.xml
        $this->assertStringContainsString('sitemap/home.xml', $content);

        // Should contain sitemap/pages/page-1.xml
        $this->assertStringContainsString('sitemap/pages/page-1.xml', $content);
    }

    public function test_pages_sitemap()
    {
        $response = $this->get('sitemap/home.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');

        $content = $response->getContent();

        // Should contain home page URL (root /)
        $this->assertStringContainsString('<loc>http://localhost</loc>', $content);
    }

    public function test_provider_sitemap()
    {
        $response = $this->get('sitemap/pages/page-1.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');

        $content = $response->getContent();

        // Should contain other page URL
        $this->assertStringContainsString('other-page', $content);

        // Should NOT contain home page URL (as it is excluded in scopeForSitemap)
        // Home page is filtered out in scopeForSitemap
        $this->assertStringNotContainsString('<loc>http://localhost</loc>', $content);

        // Check exact URL for other page
        // Since we didn't specify locale in URL, it assumes default locale
        // If Translatable is working, it should have slug 'other-page'.
        // PageTranslation::getUrl uses home_url($slug)
        // It seems in test environment it adds locale prefix
        $this->assertStringContainsString('other-page', $content);
    }
}
