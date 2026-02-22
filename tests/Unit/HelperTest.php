<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Juzaweb\Modules\Core\Tests\TestCase;

class HelperTest extends TestCase
{
    public function test_sub_char()
    {
        // Test short string
        $this->assertEquals('Short', sub_char('Short', 10));

        // Test string with exact length n
        $this->assertEquals('Hello', sub_char('Hello', 5));

        // Test truncate with spaces
        $this->assertEquals('Hello...', sub_char('Hello world', 7));

        // Test custom suffix
        $this->assertEquals('Hello---', sub_char('Hello world', 7, '---'));

        // Test multi-byte characters
        // 'Chủ nhật' has 8 characters and 10 bytes.
        // If we want 5 characters, it should be 'Chủ...'
        $this->assertEquals('Chủ...', sub_char('Chủ nhật', 5));

        // Test multi-byte string shorter than n characters but more bytes than n
        // 'Chủ nhật' is 8 characters but 10 bytes.
        $this->assertEquals('Chủ nhật', sub_char('Chủ nhật', 9));

        // Test with no spaces in the truncated part
        // Current behavior: returns '...' because mb_strrpos returns false
        // We probably want 'Hello...' if we truncate 'Helloworld' at 5
        $this->assertEquals('Hello...', sub_char('Helloworld', 5));
    }
}
