<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Widget as WidgetContract;
use Juzaweb\Modules\Core\Facades\Widget;
use Juzaweb\Modules\Core\Support\Entities\Widget as WidgetEntity;
use Juzaweb\Modules\Core\Support\WidgetRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class WidgetFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(WidgetRepository::class, Widget::getFacadeRoot());
        $this->assertInstanceOf(WidgetContract::class, Widget::getFacadeRoot());
    }

    public function test_make_and_get_widget()
    {
        Widget::make(
            'test_widget',
            fn() => [
                'label' => 'Test Widget',
                'description' => 'Test Description',
            ]
        );

        $widget = Widget::get('test_widget');

        $this->assertInstanceOf(WidgetEntity::class, $widget);
        $this->assertEquals('Test Widget', $widget->label);
        $this->assertEquals('Test Description', $widget->description);
    }

    public function test_all_widgets()
    {
        Widget::make('widget1', fn() => ['label' => 'Widget 1']);
        Widget::make('widget2', fn() => ['label' => 'Widget 2']);

        $widgets = Widget::all();

        $this->assertCount(2, $widgets);
        $this->assertTrue($widgets->has('widget1'));
        $this->assertTrue($widgets->has('widget2'));
        $this->assertInstanceOf(WidgetEntity::class, $widgets->get('widget1'));
        $this->assertEquals('Widget 1', $widgets->get('widget1')->label);
    }

    public function test_get_returns_null_for_unknown_widget()
    {
        $this->assertNull(Widget::get('unknown_widget'));
    }
}
