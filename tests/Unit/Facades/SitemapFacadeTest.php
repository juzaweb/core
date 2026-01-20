<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\Sitemap as SitemapContract;
use Juzaweb\Modules\Core\Facades\Sitemap;
use Juzaweb\Modules\Core\Support\SitemapRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class SitemapFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(SitemapRepository::class, Sitemap::getFacadeRoot());
        $this->assertInstanceOf(SitemapContract::class, Sitemap::getFacadeRoot());
    }

    public function test_register_and_get_sitemap()
    {
        // Test register
        Sitemap::register('posts', 'Juzaweb\Modules\Core\Models\Post');
        Sitemap::register('pages', 'Juzaweb\Modules\Core\Models\Page');

        // Test get
        $this->assertEquals('Juzaweb\Modules\Core\Models\Post', Sitemap::get('posts'));
        $this->assertEquals('Juzaweb\Modules\Core\Models\Page', Sitemap::get('pages'));
        $this->assertNull(Sitemap::get('non-existent'));

        // Test all
        $all = Sitemap::all();
        $this->assertInstanceOf(Collection::class, $all);
        $this->assertCount(2, $all);
        $this->assertArrayHasKey('posts', $all);
        $this->assertArrayHasKey('pages', $all);
        $this->assertEquals('Juzaweb\Modules\Core\Models\Post', $all['posts']);
    }

    public function test_facade_should_receive_method()
    {
        Sitemap::shouldReceive('get')
            ->once()
            ->with('posts')
            ->andReturn('Juzaweb\Modules\Core\Models\Post');

        $result = Sitemap::get('posts');

        $this->assertEquals('Juzaweb\Modules\Core\Models\Post', $result);
    }
}
