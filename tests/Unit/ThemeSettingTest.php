<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Juzaweb\Modules\Core\Facades\ThemeSetting;
use Juzaweb\Modules\Core\Tests\TestCase;

class ThemeSettingTest extends TestCase
{
    public function testSetAndGet()
    {
        ThemeSetting::set('test_key', 'test_value');

        $this->assertEquals('test_value', ThemeSetting::get('test_key'));

        // Test update
        ThemeSetting::set('test_key', 'updated_value');
        $this->assertEquals('updated_value', ThemeSetting::get('test_key'));
    }

    public function testGetDefault()
    {
        $this->assertEquals('default_value', ThemeSetting::get('non_existent_key', 'default_value'));
    }

    public function testTypeCasting()
    {
        ThemeSetting::set('int_key', '123');
        ThemeSetting::set('float_key', '12.34');
        ThemeSetting::set('bool_key_true', 'true');
        ThemeSetting::set('bool_key_false', 'false');
        ThemeSetting::set('bool_key_1', '1');
        ThemeSetting::set('bool_key_0', '0');

        $this->assertSame(123, ThemeSetting::integer('int_key'));
        $this->assertSame(12.34, ThemeSetting::float('float_key'));

        $this->assertTrue(ThemeSetting::boolean('bool_key_true'));
        $this->assertFalse(ThemeSetting::boolean('bool_key_false'));
        $this->assertTrue(ThemeSetting::boolean('bool_key_1'));
        $this->assertFalse(ThemeSetting::boolean('bool_key_0'));
    }

    public function testBulkOperations()
    {
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        ThemeSetting::sets($data);

        $results = ThemeSetting::gets(['key1', 'key2']);

        $this->assertEquals($data, $results);
        $this->assertEquals('value1', ThemeSetting::get('key1'));
        $this->assertEquals('value2', ThemeSetting::get('key2'));
    }

    public function testDatabasePersistence()
    {
        ThemeSetting::set('db_key', 'db_value');

        $this->assertDatabaseHas('theme_settings', [
            'code' => 'db_key',
            'value' => 'db_value',
        ]);
    }
}
