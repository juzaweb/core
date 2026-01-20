<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Breadcrumb as BreadcrumbContract;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Support\BreadcrumbFactory;
use Juzaweb\Modules\Core\Tests\TestCase;

class BreadcrumbFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(BreadcrumbFactory::class, Breadcrumb::getFacadeRoot());
        $this->assertInstanceOf(BreadcrumbContract::class, Breadcrumb::getFacadeRoot());
    }

    public function test_add_and_get_items()
    {
        Breadcrumb::add('Home', '/');
        Breadcrumb::add('Dashboard', '/dashboard');

        $items = Breadcrumb::getItems();

        $this->assertCount(2, $items);
        $this->assertEquals([
            'title' => 'Home',
            'url' => '/',
        ], $items[0]);
        $this->assertEquals([
            'title' => 'Dashboard',
            'url' => '/dashboard',
        ], $items[1]);
    }

    public function test_items_method_replaces_all_items()
    {
        Breadcrumb::add('Old', '/old');

        $newItems = [
            [
                'title' => 'New 1',
                'url' => '/new1',
            ],
            [
                'title' => 'New 2',
                'url' => '/new2',
            ]
        ];

        Breadcrumb::items($newItems);

        $this->assertEquals($newItems, Breadcrumb::getItems());
    }

    public function test_add_items_merges_items()
    {
        Breadcrumb::add('First', '/first');

        $moreItems = [
            [
                'title' => 'Second',
                'url' => '/second',
            ]
        ];

        Breadcrumb::addItems($moreItems);

        $items = Breadcrumb::getItems();
        $this->assertCount(2, $items);
        $this->assertEquals('First', $items[0]['title']);
        $this->assertEquals('Second', $items[1]['title']);
    }

    public function test_to_array_returns_items()
    {
        Breadcrumb::add('Test', '/test');

        $this->assertEquals(Breadcrumb::getItems(), Breadcrumb::toArray());
    }

    public function test_facade_mocking()
    {
        Breadcrumb::shouldReceive('getItems')
            ->once()
            ->andReturn([['title' => 'Mocked', 'url' => '/mocked']]);

        $items = Breadcrumb::getItems();

        $this->assertEquals([['title' => 'Mocked', 'url' => '/mocked']], $items);
    }
}
