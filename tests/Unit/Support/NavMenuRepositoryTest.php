<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Support;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Support\NavMenuRepository;
use Juzaweb\Modules\Core\Tests\TestCase;
use ReflectionClass;

class NavMenuRepositoryTest extends TestCase
{
    protected NavMenuRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new NavMenuRepository();
    }

    public function test_make_stores_callback_with_key()
    {
        $key = 'test_menu';
        $callback = function () {
            return ['test' => 'value'];
        };

        $this->repository->make($key, $callback);

        $reflection = new ReflectionClass($this->repository);
        $property = $reflection->getProperty('navMenus');
        $property->setAccessible(true);
        $navMenus = $property->getValue($this->repository);

        $this->assertArrayHasKey($key, $navMenus);
        $this->assertSame($callback, $navMenus[$key]);
    }

    public function test_get_returns_evaluated_callback_result()
    {
        $key = 'test_menu';
        $expectedResult = ['item1' => 'value1'];
        $callback = function () use ($expectedResult) {
            return $expectedResult;
        };

        $this->repository->make($key, $callback);

        $this->assertEquals($expectedResult, $this->repository->get($key));
    }

    public function test_get_returns_null_when_key_does_not_exist()
    {
        $this->assertNull($this->repository->get('non_existent_menu'));
    }

    public function test_all_evaluates_and_returns_all_callbacks()
    {
        $key1 = 'test_menu_1';
        $expectedResult1 = ['item1' => 'value1'];
        $callback1 = function () use ($expectedResult1) {
            return $expectedResult1;
        };

        $key2 = 'test_menu_2';
        $expectedResult2 = ['item2' => 'value2'];
        $callback2 = function () use ($expectedResult2) {
            return $expectedResult2;
        };

        $this->repository->make($key1, $callback1);
        $this->repository->make($key2, $callback2);

        $all = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertCount(2, $all);
        $this->assertEquals($expectedResult1, $all->get($key1));
        $this->assertEquals($expectedResult2, $all->get($key2));
    }

    public function test_all_returns_empty_collection_when_no_menus()
    {
        $all = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertCount(0, $all);
    }
}
