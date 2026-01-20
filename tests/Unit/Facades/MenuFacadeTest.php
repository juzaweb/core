<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Menu as MenuContract;
use Juzaweb\Modules\Core\Facades\Menu;
use Juzaweb\Modules\Core\Support\MenuRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class MenuFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(MenuRepository::class, Menu::getFacadeRoot());
        $this->assertInstanceOf(MenuContract::class, Menu::getFacadeRoot());
    }

    public function test_make_and_get()
    {
        Menu::make('test-menu', function () {
            return [
                'title' => 'Test Menu',
                'url' => 'test-menu',
                'icon' => 'fa fa-test',
                'position' => 'test-position',
            ];
        });

        $menu = Menu::get('test-menu');

        $this->assertIsArray($menu);
        $this->assertEquals('Test Menu', $menu['title']);
        $this->assertEquals('test-menu', $menu['url']);
        $this->assertEquals('fa fa-test', $menu['icon']);
        $this->assertEquals('test-position', $menu['position']);
    }

    public function test_get_returns_null_for_unknown_key()
    {
        $this->assertNull(Menu::get('unknown-key'));
    }

    public function test_get_by_position()
    {
        Menu::make('menu-1', function () {
            return [
                'title' => 'Menu 1',
                'url' => 'menu-1',
                'position' => 'pos-1',
                'priority' => 10,
            ];
        });

        Menu::make('menu-2', function () {
            return [
                'title' => 'Menu 2',
                'url' => 'menu-2',
                'position' => 'pos-1',
                'priority' => 5,
            ];
        });

        Menu::make('menu-3', function () {
            return [
                'title' => 'Menu 3',
                'url' => 'menu-3',
                'position' => 'pos-2',
            ];
        });

        $menus = Menu::getByPosition('pos-1');

        $this->assertCount(2, $menus);

        $items = $menus->toArray();

        $titles = array_column($items, 'title');
        $this->assertContains('Menu 1', $titles);
        $this->assertContains('Menu 2', $titles);
        $this->assertNotContains('Menu 3', $titles);

        // Verify default values
        $menu1 = collect($items)->firstWhere('key', 'menu-1');
        $this->assertEquals('fa fa-circle', $menu1['icon']); // Default icon
        $this->assertEquals('_self', $menu1['target']); // Default target
        $this->assertEquals(10, $menu1['priority']);
    }

    public function test_get_by_position_url_generation()
    {
        // Test auto URL generation based on key and prefix
        Menu::make('dashboard', function () {
            return [
                'title' => 'Dashboard',
                'position' => 'admin-left',
                'icon' => 'fa fa-dashboard',
            ];
        });

        Menu::make('users', function () {
            return [
                'title' => 'Users',
                'position' => 'admin-left',
            ];
        });

        $menus = Menu::getByPosition('admin-left');
        $dashboard = $menus->firstWhere('key', 'dashboard');
        $users = $menus->firstWhere('key', 'users');

        $this->assertEquals(url('admin'), $dashboard['url']);

        // Users with key 'users' and default prefix 'admin' -> url('admin/users')
        $this->assertEquals(url('admin/users'), $users['url']);
    }

    public function test_all()
    {
        Menu::make('item-1', function () { return ['title' => 'Item 1']; });
        Menu::make('item-2', function () { return ['title' => 'Item 2']; });

        $all = Menu::all();

        $this->assertCount(2, $all);
        $this->assertEquals('Item 1', $all['item-1']['title']);
        $this->assertEquals('Item 2', $all['item-2']['title']);
    }

    public function test_facade_mocking()
    {
        Menu::shouldReceive('get')
            ->once()
            ->with('mock-key')
            ->andReturn(['title' => 'Mocked Menu']);

        $result = Menu::get('mock-key');

        $this->assertEquals(['title' => 'Mocked Menu'], $result);
    }
}
