<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Traits;

use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Traits\HasContent;
use Webwizo\Shortcodes\Facades\Shortcode;
use Webwizo\Shortcodes\ShortcodesServiceProvider;

class HasContentTest extends TestCase
{
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            ShortcodesServiceProvider::class,
        ]);
    }

    protected function getPackageAliases($app): array
    {
        return array_merge(parent::getPackageAliases($app), [
            'Shortcode' => Shortcode::class,
        ]);
    }

    public function testRenderContentWithProxyImage()
    {
        $model = new class {
            use HasContent;
            public $content;
        };

        $baseUrl = url('/');
        $alreadyProxyUrl = "{$baseUrl}/images/original/somehash/image.jpg";
        $normalUrl = "http://example.com/image.jpg";

        $model->content = "<img src='{$alreadyProxyUrl}' /><img src='{$normalUrl}' />";

        // We expect the already proxy url to remain unchanged (or at least not double proxied)
        // And the normal url to be proxied.

        $rendered = $model->renderContent();

        $this->assertStringContainsString($alreadyProxyUrl, $rendered, "Already proxied image should not be modified");

        // Check normal url is proxied
        $this->assertStringNotContainsString("src='{$normalUrl}'", $rendered, "Normal image should be proxied");
        $this->assertStringContainsString("{$baseUrl}/images/original/", $rendered);
    }
}
