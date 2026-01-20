<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Chart as ChartContract;
use Juzaweb\Modules\Core\Facades\Chart;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Support\ChartRepository;

class ChartFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(ChartRepository::class, Chart::getFacadeRoot());
        $this->assertInstanceOf(ChartContract::class, Chart::getFacadeRoot());
    }

    public function test_facade_method_calls()
    {
        // Mock the underlying service
        Chart::shouldReceive('get')
            ->once()
            ->with('non-existent-chart')
            ->andReturn(null);

        $result = Chart::get('non-existent-chart');

        $this->assertNull($result);
    }
}
