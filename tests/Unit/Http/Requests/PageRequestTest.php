<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Http\Requests;

use Juzaweb\Modules\Core\Http\Requests\PageRequest;
use Orchestra\Testbench\TestCase;

class PageRequestTest extends TestCase
{
    public function test_sanitize_title_and_content()
    {
        $request = new PageRequest;

        $request->merge([
            'title' => '<script>alert("xss")</script>Test Title',
            'content' => '<p>Test</p><script>alert("xss")</script><iframe src="javascript:alert(1)"></iframe>',
        ]);

        $request->setContainer($this->app);

        $method = new \ReflectionMethod(PageRequest::class, 'prepareForValidation');
        $method->setAccessible(true);
        $method->invoke($request);

        $this->assertEquals('Test Title', $request->input('title'));
        $this->assertEquals('<p>Test</p>', trim($request->input('content')));
    }
}
