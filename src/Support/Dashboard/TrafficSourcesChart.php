<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support\Dashboard;

use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\Filter\StringFilter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Juzaweb\Modules\Core\Support\Charts\PieChart;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class TrafficSourcesChart extends PieChart
{
    public string $id = 'traffic-sources';

    public function getTitle(): string
    {
        return __('core::translation.traffic_sources');
    }

    public function getIcon(): string
    {
        return 'share-alt';
    }

    public function getData(): array
    {
        $response = Analytics::get(
            period: new Period(now()->subDays(8), now()->subDay()),
            metrics: ['sessions'],
            dimensions: ['sessionSource'],
        );

        $rows = collect($response)->sortByDesc('sessions');

        return [
            'labels' => $rows->pluck('sessionSource')->values(),
            'datasets' => [
                [
                    'label' => __('core::translation.sessions'),
                    'data' => $rows->pluck('sessions')->values(),
                ],
            ],
        ];
    }
}
