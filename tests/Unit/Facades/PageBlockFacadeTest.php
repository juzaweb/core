<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\PageBlock as PageBlockContract;
use Juzaweb\Modules\Core\Facades\PageBlock;
use Juzaweb\Modules\Core\Support\Entities\PageBlock as PageBlockEntity;
use Juzaweb\Modules\Core\Support\PageBlockRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class PageBlockFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(PageBlockRepository::class, PageBlock::getFacadeRoot());
        $this->assertInstanceOf(PageBlockContract::class, PageBlock::getFacadeRoot());
    }

    public function test_make_and_get_page_block()
    {
        $key = 'test_block';
        $options = [
            'label' => 'Test Block',
            'form' => 'test::form',
            'view' => 'test::view'
        ];

        PageBlock::make($key, function () use ($options) {
            return $options;
        });

        $block = PageBlock::get($key);

        $this->assertInstanceOf(PageBlockEntity::class, $block);
        $this->assertEquals($key, $block->key);
        $this->assertEquals($options['label'], $block->label);
        $this->assertEquals($options['form'], $block->form);
        $this->assertEquals($options['view'], $block->view);
    }

    public function test_get_returns_null_for_non_existent_block()
    {
        $this->assertNull(PageBlock::get('non_existent_block'));
    }

    public function test_all_returns_collection_of_blocks()
    {
        // Clear any existing blocks (though setup should handle it, explicit is better if we are depending on count)
        // However, PageBlockRepository doesn't have a clear method, but it's a new instance per app boot (test setup).

        $key1 = 'block1';
        $options1 = ['label' => 'Block 1', 'form' => 'f1', 'view' => 'v1'];

        $key2 = 'block2';
        $options2 = ['label' => 'Block 2', 'form' => 'f2', 'view' => 'v2'];

        PageBlock::make($key1, fn() => $options1);
        PageBlock::make($key2, fn() => $options2);

        $all = PageBlock::all();

        $this->assertInstanceOf(Collection::class, $all);
        $this->assertTrue($all->has($key1));
        $this->assertTrue($all->has($key2));
        $this->assertInstanceOf(PageBlockEntity::class, $all->get($key1));
        $this->assertInstanceOf(PageBlockEntity::class, $all->get($key2));
    }
}
