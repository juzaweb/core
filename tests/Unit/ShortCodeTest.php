<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Juzaweb\Modules\Core\Facades\ShortCode;
use Juzaweb\Modules\Core\Tests\TestCase;

class ShortCodeTest extends TestCase
{
    public function test_register_and_compile_simple_shortcode()
    {
        ShortCode::register('test', function () {
            return 'Test Shortcode';
        });

        $content = 'This is a [test]';
        $compiled = ShortCode::compile($content);

        $this->assertEquals('This is a Test Shortcode', $compiled);
    }

    public function test_compile_shortcode_with_attributes()
    {
        ShortCode::register('link', function ($attr) {
            return '<a href="' . ($attr['url'] ?? '#') . '">' . ($attr['text'] ?? '') . '</a>';
        });

        $content = 'Click [link url="https://example.com" text="Here"]';
        $compiled = ShortCode::compile($content);

        $this->assertEquals('Click <a href="https://example.com">Here</a>', $compiled);
    }

    public function test_compile_shortcode_with_content()
    {
        ShortCode::register('bold', function ($attr, $content) {
            return '<b>' . $content . '</b>';
        });

        $content = 'This is [bold]bold text[/bold]';
        $compiled = ShortCode::compile($content);

        $this->assertEquals('This is <b>bold text</b>', $compiled);
    }

    public function test_unknown_shortcode_remains_unchanged()
    {
        $content = 'This is [unknown]';
        $compiled = ShortCode::compile($content);

        $this->assertEquals('This is [unknown]', $compiled);
    }
}
