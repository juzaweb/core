<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Thumbnail as ThumbnailContract;
use Juzaweb\Modules\Core\Facades\Thumbnail;
use Juzaweb\Modules\Core\Support\ThumbnailRepository;
use Juzaweb\Modules\Core\Tests\TestCase;

class ThumbnailFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(ThumbnailRepository::class, Thumbnail::getFacadeRoot());
        $this->assertInstanceOf(ThumbnailContract::class, Thumbnail::getFacadeRoot());
    }

    public function test_defaults_and_get_defaults()
    {
        $defaults = [
            'width' => 100,
            'height' => 100,
        ];

        Thumbnail::defaults(function () use ($defaults) {
            return $defaults;
        });

        $this->assertEquals($defaults, Thumbnail::getDefaults());
    }

    public function test_get_defaults_returns_empty_array_if_not_set()
    {
        $this->assertEquals([], Thumbnail::getDefaults());
    }

    public function test_facade_mocking()
    {
        $defaults = ['mocked' => true];

        Thumbnail::shouldReceive('getDefaults')
            ->once()
            ->andReturn($defaults);

        $this->assertEquals($defaults, Thumbnail::getDefaults());
    }
}
