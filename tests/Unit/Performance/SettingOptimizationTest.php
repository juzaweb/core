<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Performance;

use Juzaweb\Modules\Core\Facades\Setting;
use Juzaweb\Modules\Core\Facades\ThemeSetting;
use Juzaweb\Modules\Core\Tests\TestCase;
use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Models\Setting as SettingModel;
use Juzaweb\Modules\Core\Models\ThemeSetting as ThemeSettingModel;

class SettingOptimizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        File::put(storage_path('app/installed'), 'installed');
        // Clear any existing settings to ensure clean state
        SettingModel::query()->delete();
        ThemeSettingModel::query()->delete();
    }

    protected function tearDown(): void
    {
        File::delete(storage_path('app/installed'));
        parent::tearDown();
    }

    public function test_optimistic_json_decoding_behavior_setting()
    {
        // 1. Plain String
        Setting::set('plain_string', 'hello world');
        $this->assertEquals('hello world', Setting::get('plain_string'));

        // 2. Array (stored as JSON)
        $arrayData = ['key' => 'value', 'nested' => [1, 2]];
        Setting::set('array_data', $arrayData);
        $this->assertEquals($arrayData, Setting::get('array_data'));

        // 3. JSON-like string (Invalid JSON)
        $invalidJson = '{key: value}'; // Missing quotes on key
        Setting::set('invalid_json', $invalidJson);
        $this->assertEquals($invalidJson, Setting::get('invalid_json'));

        // 4. Valid JSON string manually set
        $jsonString = '{"valid": "json"}';
        Setting::set('manual_json', $jsonString);
        $this->assertEquals(['valid' => 'json'], Setting::get('manual_json'));

        // 5. Numeric String
        Setting::set('numeric_string', '12345');
        // Expect 12345. Use loose comparison as existing architecture might cast to string.
        $this->assertEquals(12345, Setting::get('numeric_string'));

        // 6. Boolean String
        Setting::set('bool_string', 'true');
        $this->assertEquals(true, Setting::get('bool_string'));
    }

    public function test_optimistic_json_decoding_behavior_theme_setting()
    {
        // 1. Plain String
        ThemeSetting::set('plain_string', 'hello world');
        $this->assertEquals('hello world', ThemeSetting::get('plain_string'));

        // 2. Array
        $arrayData = ['key' => 'value'];
        ThemeSetting::set('array_data', $arrayData);
        $this->assertEquals($arrayData, ThemeSetting::get('array_data'));

        // 3. Invalid JSON
        $invalidJson = 'foo bar';
        ThemeSetting::set('invalid_json', $invalidJson);
        $this->assertEquals($invalidJson, ThemeSetting::get('invalid_json'));

        // 4. Numeric
        ThemeSetting::set('numeric', '999');
        $this->assertEquals(999, ThemeSetting::get('numeric'));
    }
}
