<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\NavMenu as NavMenuContract;
use Juzaweb\Modules\Core\Facades\NavMenu;
use Juzaweb\Modules\Core\Support\NavMenuRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class NavMenuFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(NavMenuRepository::class, NavMenu::getFacadeRoot());
        $this->assertInstanceOf(NavMenuContract::class, NavMenu::getFacadeRoot());
    }

    public function test_make_and_get_nav_menu()
    {
        NavMenu::make('test_menu', function () {
            return ['item1', 'item2'];
        });

        $menu = NavMenu::get('test_menu');

        $this->assertIsArray($menu);
        $this->assertEquals(['item1', 'item2'], $menu);
    }

    public function test_get_returns_null_for_unknown_key()
    {
        $this->assertNull(NavMenu::get('unknown_key'));
    }

    public function test_all_returns_collection_of_results()
    {
        NavMenu::make('menu1', function () {
            return ['m1'];
        });

        NavMenu::make('menu2', function () {
            return ['m2'];
        });

        $all = NavMenu::all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertEquals(['m1'], $all->get('menu1'));
        $this->assertEquals(['m2'], $all->get('menu2'));
    }

    public function test_facade_mocking()
    {
        NavMenu::shouldReceive('get')
            ->once()
            ->with('mocked_key')
            ->andReturn(['mocked_item']);

        $result = NavMenu::get('mocked_key');

        $this->assertEquals(['mocked_item'], $result);
    }
}
