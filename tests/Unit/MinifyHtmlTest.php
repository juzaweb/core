<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Support\BladeMinify\MinifyHtml;

class MinifyHtmlTest extends TestCase
{
    public function testMinifyHandlesMultiLineAttributes()
    {
        $html = '<div class="
  foo
  bar
">Content</div>';

        $minified = MinifyHtml::minify($html);

        // We expect the multi-line attribute to be preserved including indentation
        // because the minifier should not touch inside of tags.
        $this->assertStringContainsString('  foo', $minified, 'Indentation for foo was lost');
        $this->assertStringContainsString('  bar', $minified, 'Indentation for bar was lost');
    }

    public function testMinifyHandlesQuotedGt()
    {
        $html = '<div data-val="foo > bar">Content</div>';
        $minified = MinifyHtml::minify($html);

        $this->assertStringContainsString('data-val="foo > bar"', $minified);
    }

    public function testMinifyHandlesMultiLineTitle()
    {
         $html = '<a title="Line 1
          Line 2">Link</a>';
         $minified = MinifyHtml::minify($html);

         $this->assertStringContainsString('          Line 2', $minified);
    }
}
