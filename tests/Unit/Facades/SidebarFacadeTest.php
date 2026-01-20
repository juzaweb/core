<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\Sidebar as SidebarContract;
use Juzaweb\Modules\Core\Facades\Sidebar;
use Juzaweb\Modules\Core\Support\Entities\Sidebar as SidebarEntity;
use Juzaweb\Modules\Core\Support\SidebarRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class SidebarFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(SidebarRepository::class, Sidebar::getFacadeRoot());
        $this->assertInstanceOf(SidebarContract::class, Sidebar::getFacadeRoot());
    }

    public function test_make_and_all_methods()
    {
        Sidebar::make('sidebar_1', function ($key) {
            return [
                'label' => 'Sidebar 1 Label',
                'description' => 'Description for sidebar 1',
            ];
        });

        Sidebar::make('sidebar_2', function ($key) {
            return [
                'label' => 'Sidebar 2 Label',
            ];
        });

        $all = Sidebar::all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertCount(2, $all);

        $first = $all['sidebar_1'];
        $this->assertInstanceOf(SidebarEntity::class, $first);
        $this->assertEquals('Sidebar 1 Label', $first->label);
        $this->assertEquals('Description for sidebar 1', $first->description);
        $this->assertEquals('Sidebar 1 Label', $first->get('label'));

        $second = $all['sidebar_2'];
        $this->assertInstanceOf(SidebarEntity::class, $second);
        $this->assertEquals('Sidebar 2 Label', $second->label);
        $this->assertNull($second->description);
    }

    public function test_facade_mocking()
    {
        Sidebar::shouldReceive('all')
            ->once()
            ->andReturn(collect(['mocked_key' => 'mocked_value']));

        $result = Sidebar::all();

        $this->assertEquals(['mocked_key' => 'mocked_value'], $result->all());
    }
}
