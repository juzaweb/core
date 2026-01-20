<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\MenuBox as MenuBoxContract;
use Juzaweb\Modules\Core\Facades\MenuBox;
use Juzaweb\Modules\Core\Support\MenuBoxRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class MenuBoxFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(MenuBoxRepository::class, MenuBox::getFacadeRoot());
        $this->assertInstanceOf(MenuBoxContract::class, MenuBox::getFacadeRoot());
    }

    public function test_make_and_get_methods()
    {
        $key = 'test_menu_box';
        $class = 'Test\Class';
        $options = function () {
            return ['priority' => 10, 'title' => 'Test Box'];
        };

        MenuBox::make($key, $class, $options);

        $box = MenuBox::get($key);

        $this->assertIsArray($box);
        $this->assertEquals($class, $box['class']);
        $this->assertEquals($options, $box['options']);
    }

    public function test_all_method_with_sorting()
    {
        MenuBox::make('box2', 'Class2', function () {
            return ['priority' => 20];
        });

        MenuBox::make('box1', 'Class1', function () {
            return ['priority' => 10];
        });

        MenuBox::make('box3', 'Class3', function () {
            return ['priority' => 5];
        });

        $all = MenuBox::all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertCount(3, $all);

        $values = $all->values();

        // Expected order: box3 (5), box1 (10), box2 (20)
        $this->assertEquals('Class3', $values[0]['class']);
        $this->assertEquals('Class1', $values[1]['class']);
        $this->assertEquals('Class2', $values[2]['class']);
    }

    public function test_facade_mocking()
    {
        MenuBox::shouldReceive('get')
            ->once()
            ->with('mocked_key')
            ->andReturn(['class' => 'MockedClass']);

        $result = MenuBox::get('mocked_key');

        $this->assertEquals(['class' => 'MockedClass'], $result);
    }
}
