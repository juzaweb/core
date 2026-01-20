<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Theme as ThemeContract;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Themes\Theme as ConcreteTheme;
use Juzaweb\Modules\Core\Themes\ThemeRepository;

class ThemeFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(ThemeRepository::class, Theme::getFacadeRoot());
        $this->assertInstanceOf(ThemeContract::class, Theme::getFacadeRoot());
    }

    public function test_facade_method_calls()
    {
        // Mock the underlying service
        Theme::shouldReceive('find')
            ->once()
            ->with('non-existent-theme')
            ->andReturn(null);

        $result = Theme::find('non-existent-theme');

        $this->assertNull($result);
    }

    public function test_facade_integration_with_dummy_theme()
    {
        // "itech" is created in TestCase::setUp()
        $theme = Theme::find('itech');

        $this->assertInstanceOf(ConcreteTheme::class, $theme);
        $this->assertEquals('itech', $theme->name());
        $this->assertEquals('Itech Theme', $theme->title());
    }
}
