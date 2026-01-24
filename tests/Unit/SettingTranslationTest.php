<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Facades\Setting;
use Juzaweb\Modules\Core\Models\Setting as SettingModel;
use Juzaweb\Modules\Core\Tests\TestCase;

class SettingTranslationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Create installed file to bypass File::missing check in configs()
        $path = storage_path('app/installed');
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }
        File::put($path, 'installed');
    }

    protected function tearDown(): void
    {
        File::delete(storage_path('app/installed'));
        parent::tearDown();
    }

    public function testTranslatableSetting()
    {
        // Create a translatable setting
        // We need to disable events to prevent automatic caching or other side effects if any
        $setting = SettingModel::create([
            'code' => 'translatable_key',
            'value' => 'fallback_value', // Default value
            'translatable' => true,
        ]);

        // Add translations
        // Using Astrotomic syntax
        $setting->translateOrNew('en')->fill([
            'lang_value' => 'Value in English',
            'setting_code' => 'translatable_key',
        ])->save();
        $setting->translateOrNew('fr')->fill([
            'lang_value' => 'Value in French',
            'setting_code' => 'translatable_key',
        ])->save();

        // Ensure cache is clear
        SettingModel::flushQueryCache();

        // We need to ensure SettingRepository reloads configs.
        // Calling set() clears the internal cache.
        Setting::set('dummy_trigger', '1');

        // Verify English
        app()->setLocale('en');
        // Clear internal cache again because we switched locale and previous call might have cached 'en'
        Setting::set('dummy_trigger', '2');

        $this->assertEquals('Value in English', Setting::get('translatable_key'));

        // Verify French
        app()->setLocale('fr');
        Setting::set('dummy_trigger', '3'); // Clear cache

        $this->assertEquals('Value in French', Setting::get('translatable_key'));
    }
}
