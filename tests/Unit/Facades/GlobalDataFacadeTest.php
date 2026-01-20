<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\GlobalData as GlobalDataContract;
use Juzaweb\Modules\Core\Facades\GlobalData;
use Juzaweb\Modules\Core\Support\GlobalDataRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class GlobalDataFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(GlobalDataRepository::class, GlobalData::getFacadeRoot());
        $this->assertInstanceOf(GlobalDataContract::class, GlobalData::getFacadeRoot());
    }

    public function test_set_and_get_methods()
    {
        // GlobalDataRepository::set requires value to be an array
        GlobalData::set('test_key', ['test_value']);
        $this->assertEquals(['test_value'], GlobalData::get('test_key'));

        GlobalData::set('nested.key', ['nested_value']);
        $this->assertEquals(['nested_value'], GlobalData::get('nested.key'));

        $this->assertEquals(['default'], GlobalData::get('non_existent', ['default']));

        // Test deep access
        GlobalData::set('config', ['foo' => 'bar']);
        $this->assertEquals('bar', GlobalData::get('config.foo'));
    }

    public function test_push_method()
    {
        GlobalData::set('list', ['item1']);
        GlobalData::push('list', 'item2');

        $this->assertEquals(['item1', 'item2'], GlobalData::get('list'));
    }

    public function test_collect_method()
    {
        GlobalData::set('collection_key', ['a' => 1, 'b' => 2]);
        $collection = GlobalData::collect('collection_key');

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertEquals(1, $collection->get('a'));
        $this->assertEquals(2, $collection->get('b'));
    }

    public function test_all_method()
    {
        // GlobalDataRepository::set requires value to be an array
        GlobalData::set('all_test_1', [1]);
        GlobalData::set('all_test_2', [2]);

        $all = GlobalData::all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertEquals([1], $all->get('all_test_1'));
        $this->assertEquals([2], $all->get('all_test_2'));
    }
}
