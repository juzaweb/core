<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Facades\Locale;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Support\LocaleRepository;
use Juzaweb\Modules\Core\Facades\Setting;
use Juzaweb\Modules\Core\Translations\Models\Language;
use Illuminate\Http\Request;

class LocaleFacadeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create installed file to enable settings
        $path = storage_path('app/installed');
        if (!File::exists(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }
        File::put($path, '1');
    }

    protected function tearDown(): void
    {
        $path = storage_path('app/installed');
        if (File::exists($path)) {
            File::delete($path);
        }
        parent::tearDown();
    }

    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(LocaleRepository::class, Locale::getFacadeRoot());
    }

    public function test_set_locale_without_prefix_setting()
    {
        Setting::set('multiple_language', 'none');

        $this->assertEquals('en', Locale::setLocale('en'));
        $this->assertNull(Locale::setLocale(null));
    }

    public function test_set_locale_with_prefix_setting_and_valid_locale()
    {
        Setting::set('multiple_language', 'prefix');

        Language::updateOrCreate(['code' => 'en'], ['name' => 'English']);

        $this->assertEquals('en', Locale::setLocale('en'));
    }

    public function test_set_locale_with_prefix_setting_and_invalid_locale()
    {
        Setting::set('multiple_language', 'prefix');

        // 'fr' does not exist
        $this->assertNull(Locale::setLocale('fr'));
    }

    public function test_set_locale_with_prefix_setting_from_request()
    {
        Setting::set('multiple_language', 'prefix');
        Language::updateOrCreate(['code' => 'en'], ['name' => 'English']);

        // Mock Request
        $request = Request::create('/en/some-page');
        $this->app->instance('request', $request);

        $this->assertEquals('en', Locale::setLocale());

        // Test invalid locale from request
        $requestInvalid = Request::create('/fr/some-page');
        $this->app->instance('request', $requestInvalid);

        $this->assertNull(Locale::setLocale());
    }
}
