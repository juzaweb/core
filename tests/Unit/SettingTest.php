<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Contracts\GlobalData;
use Juzaweb\Modules\Core\Facades\Setting;
use Juzaweb\Modules\Core\Tests\TestCase;

class SettingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        File::put(storage_path('app/installed'), 'installed');
    }

    protected function tearDown(): void
    {
        File::delete(storage_path('app/installed'));

        parent::tearDown();
    }

    public function testSetAndGet()
    {
        Setting::set('test_key', 'test_value');

        $this->assertEquals('test_value', Setting::get('test_key'));
    }

    public function testGetDefault()
    {
        $this->assertEquals('default_value', Setting::get('non_existent_key', 'default_value'));
    }

    public function testSets()
    {
        $settings = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        Setting::sets($settings);

        $this->assertEquals('value1', Setting::get('key1'));
        $this->assertEquals('value2', Setting::get('key2'));
    }

    public function testBoolean()
    {
        Setting::set('bool_true', '1');
        Setting::set('bool_false', '0');
        Setting::set('bool_string_true', 'true');
        Setting::set('bool_string_false', 'false');

        $this->assertTrue(Setting::boolean('bool_true'));
        $this->assertFalse(Setting::boolean('bool_false'));
        $this->assertTrue(Setting::boolean('bool_string_true'));
        $this->assertFalse(Setting::boolean('bool_string_false'));
    }

    public function testInteger()
    {
        Setting::set('int_val', '123');

        $this->assertEquals(123, Setting::integer('int_val'));
        $this->assertIsInt(Setting::integer('int_val'));
    }

    public function testFloat()
    {
        Setting::set('float_val', '123.45');

        $this->assertEquals(123.45, Setting::float('float_val'));
        $this->assertIsFloat(Setting::float('float_val'));
    }

    public function testAll()
    {
        $this->app[GlobalData::class]->set('settings.all_key1', [
            'key' => 'all_key1',
            'default' => 'default_value1',
        ]);
        $this->app[GlobalData::class]->set('settings.all_key2', [
            'key' => 'all_key2',
            'default' => 'default_value2',
        ]);

        Setting::set('all_key1', 'value1');
        Setting::set('all_key2', 'value2');

        $all = Setting::all();

        $this->assertArrayHasKey('all_key1', $all);
        $this->assertArrayHasKey('all_key2', $all);
        $this->assertEquals('value1', $all['all_key1']);
        $this->assertEquals('value2', $all['all_key2']);
    }

    public function testArrayValue()
    {
        $array = ['a' => 1, 'b' => 2];
        Setting::set('array_key', $array);

        $this->assertEquals($array, Setting::get('array_key'));
    }
}
