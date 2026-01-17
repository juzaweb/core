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

class UsersByCountryChart extends PieChart
{
    public string $id = 'users-by-country';

    public function getTitle(): string
    {
        return __('core::translation.top_countries_by_users');
    }

    public function getIcon(): string
    {
        return 'users';
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
            metrics: ['activeUsers'],
            dimensions: ['country'],
            dimensionFilter: website()->isMainWebsite() ? null : $filterExpression
        );

        $total = collect($response)->sum('activeUsers');
        $rows = collect($response)
            ->map(fn($row) => [
                'country' => $row['country'],
                'users' => (int) ($row['activeUsers'] * 100 / ($total > 0 ? $total : 1)),
            ])
            ->sortByDesc('users');

        $top10 = $rows->take(9);

        $othersTotal = $rows->skip(10)->sum('users');

        if ($othersTotal > 0) {
            $top10->push([
                'country' => 'Other',
                'users' => $othersTotal,
            ]);
        }

        return [
            'labels' => $top10->pluck('country')->values(),
            'datasets' => [
                [
                    'label' => __('core::translation.users'),
                    'data' => $top10->pluck('users')->values(),
                ],
            ],
        ];
    }
}
