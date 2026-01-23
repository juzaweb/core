<?php

namespace Juzaweb\Modules\Core\Tests\Unit;

use Juzaweb\Modules\Core\Contracts\Theme;
use Juzaweb\Modules\Core\Contracts\ThemeSetting as ThemeSettingContract;
use Juzaweb\Modules\Core\Facades\ThemeSetting;
use Juzaweb\Modules\Core\Models\ThemeSetting as ThemeSettingModel;
use Juzaweb\Modules\Core\Tests\TestCase;
use Mockery;

class ThemeSettingNoThemeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock the Theme contract to return null for current()
        $mock = Mockery::mock(Theme::class);
        $mock->shouldReceive('current')->andReturn(null);
        $this->app->instance(Theme::class, $mock);

        // Forget the previously resolved ThemeSetting instance so next time it is resolved,
        // it uses the mocked Theme.
        $this->app->forgetInstance(ThemeSettingContract::class);
    }

    public function testGetWithoutTheme()
    {
        // This fails if configs() tries to access current()->name()
        // We expect it to be handled gracefully (e.g. return default)
        $value = ThemeSetting::get('test_key', 'default_value');
        $this->assertEquals('default_value', $value);
    }

    public function testSetWithoutTheme()
    {
        // This fails if set() tries to access current()->name()
        // We expect it to be handled gracefully (e.g. do nothing or throw specific exception,
        // but user request implies fixing an error, likely a crash)
        $model = ThemeSetting::set('test_key', 'test_value');

        $this->assertInstanceOf(ThemeSettingModel::class, $model);
        $this->assertEquals('test_key', $model->code);
        $this->assertEquals('test_value', $model->value);
        // It should not exist in database because it has no theme
        $this->assertFalse($model->exists);
    }
}
