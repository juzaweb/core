<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Support;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Support\NavMenuRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class NavMenuRepositoryTest extends TestCase
{
    protected NavMenuRepository $navMenuRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->navMenuRepository = new NavMenuRepository();
    }

    public function test_all_returns_empty_collection_when_no_menus_registered()
    {
        $all = $this->navMenuRepository->all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertTrue($all->isEmpty());
    }

    public function test_all_returns_collection_with_one_item()
    {
        $this->navMenuRepository->make('menu1', function () {
            return ['m1'];
        });

        $all = $this->navMenuRepository->all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertCount(1, $all);
        $this->assertEquals(['m1'], $all->get('menu1'));
    }

    public function test_all_returns_collection_with_multiple_items()
    {
        $this->navMenuRepository->make('menu1', function () {
            return ['m1'];
        });

        $this->navMenuRepository->make('menu2', function () {
            return ['m2'];
        });

        $this->navMenuRepository->make('menu3', function () {
            return ['m3'];
        });

        $all = $this->navMenuRepository->all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertCount(3, $all);
        $this->assertEquals(['m1'], $all->get('menu1'));
        $this->assertEquals(['m2'], $all->get('menu2'));
        $this->assertEquals(['m3'], $all->get('menu3'));
    }
}
