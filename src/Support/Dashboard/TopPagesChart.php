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
use function Juzaweb\Modules\Admin\Support\Dashboard\website;
use function Juzaweb\Modules\Admin\Support\Dashboard\website_id;

class TopPagesChart extends PieChart
{
    public string $id = 'top-pages';

    public function getTitle(): string
    {
        return __('core::translation.top_10_pages_most_traffic');
    }

    public function getIcon(): string
    {
        return 'file';
    }

    public function getData(): array
    {
        $filter = new Filter([
            'field_name' => 'customEvent:website_id',
            'string_filter' => new StringFilter([
                'match_type' => StringFilter\MatchType::EXACT,
            ]),
        ]);

        $filterExpression = new FilterExpression([
            'filter' => $filter,
        ]);

        $response = Analytics::get(
            period: new Period(now()->subDays(8), now()->subDay()),
            metrics: ['screenPageViews'],
            dimensions: ['pagePath'],
        );

        $rows = collect($response)->sortByDesc('screenPageViews');

        return [
            'labels' => $rows->pluck('pagePath')->map(function ($path) {
                return strlen($path) > 30 ? substr($path, 0, 30) . '...' : $path;
            })->values(),
            'datasets' => [
                [
                    'label' => __('core::translation.page_views'),
                    'data' => $rows->pluck('screenPageViews')->values(),
                ],
            ],
        ];
    }
}
