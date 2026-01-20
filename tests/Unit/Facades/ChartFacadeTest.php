<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Facades;

use Juzaweb\Modules\Core\Contracts\Chart as ChartContract;
use Juzaweb\Modules\Core\Facades\Chart;
use Juzaweb\Modules\Core\Tests\TestCase;
use Juzaweb\Modules\Core\Support\ChartRepository;
use stdClass;

class ChartFacadeTest extends TestCase
{
    public function test_facade_resolves_to_implementation()
    {
        $this->assertInstanceOf(ChartRepository::class, Chart::getFacadeRoot());
        $this->assertInstanceOf(ChartContract::class, Chart::getFacadeRoot());
    }

    public function test_facade_method_calls()
    {
        // Mock the underlying service for a simple call
        Chart::shouldReceive('get')
            ->once()
            ->with('non-existent-chart')
            ->andReturn(null);

        $result = Chart::get('non-existent-chart');

        $this->assertNull($result);
    }

    public function test_chart_method_registers_chart()
    {
        Chart::chart('test_chart', stdClass::class);

        $this->assertEquals(stdClass::class, Chart::get('test_chart'));
    }

    public function test_charts_method_returns_all_charts()
    {
        Chart::chart('chart1', stdClass::class);
        Chart::chart('chart2', TestCase::class);

        $charts = Chart::charts();

        $this->assertIsArray($charts);
        $this->assertArrayHasKey('chart1', $charts);
        $this->assertArrayHasKey('chart2', $charts);
        $this->assertEquals(stdClass::class, $charts['chart1']);
        $this->assertEquals(TestCase::class, $charts['chart2']);
    }

    public function test_get_method_returns_chart_class()
    {
        Chart::chart('my_chart', stdClass::class);

        $this->assertEquals(stdClass::class, Chart::get('my_chart'));
        $this->assertNull(Chart::get('unknown_chart'));
    }

    public function test_make_method_returns_chart_instance()
    {
        Chart::chart('std_chart', stdClass::class);

        $instance = Chart::make('std_chart');

        $this->assertInstanceOf(stdClass::class, $instance);
    }
}
