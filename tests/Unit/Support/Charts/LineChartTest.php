<?php

namespace Juzaweb\Modules\Core\Tests\Unit\Support\Charts;

use Illuminate\View\View;
use Juzaweb\Modules\Core\Support\Charts\LineChart;
use Juzaweb\Modules\Core\Tests\TestCase;

class LineChartTest extends TestCase
{
    public function test_render_returns_view()
    {
        $chart = new class extends LineChart {
            public function getTitle(): string
            {
                return 'Test Line Chart';
            }

            public function getIcon(): string
            {
                return 'fa fa-test';
            }

            public function getData(): array
            {
                return [1, 2, 3];
            }
        };

        $view = $chart->render();

        $this->assertInstanceOf(View::class, $view);
        $this->assertEquals('core::components.dashboard.line-chart', $view->name());
        $this->assertArrayHasKey('chart', $view->getData());
        $this->assertSame($chart, $view->getData()['chart']);
    }

    public function test_make_returns_instance()
    {
        $chartClass = new class extends LineChart {
            public function getTitle(): string
            {
                return 'Make Test Chart';
            }

            public function getIcon(): string
            {
                return 'fa fa-make';
            }

            public function getData(): array
            {
                return [4, 5, 6];
            }
        };

        $chartClassName = get_class($chartClass);
        $chart = $chartClassName::make();

        $this->assertInstanceOf($chartClassName, $chart);
        $this->assertInstanceOf(LineChart::class, $chart);
    }
}
