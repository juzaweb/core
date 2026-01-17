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
use Illuminate\Support\Carbon;
use Juzaweb\Modules\Core\Support\Charts\LineChart;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class UsersChart extends LineChart
{
    public string $id = 'users';

    public function getTitle(): string
    {
        return __('core::translation.users_this_week');
    }

    public function getIcon(): string
    {
        return 'users';
    }

    public function getData(): array
    {
        $guard = 'web';
        $provider = config("auth.guards.{$guard}.provider");
        $model = config('auth.providers.' . $provider . '.model');

        $newUsers = $model::cacheFor(3600)
            ->whereActive()
            ->selectRaw('DATE(created_at) as date, count(*) as total')
            ->whereBetween('created_at', [now()->subDays(8), now()->subDay()])
            ->groupBy('date')
            ->pluck('total', 'date')
            ->sort(fn ($a, $b) => $a <=> $b);

        foreach (date_range(now()->subDays(8), now()->subDay()) as $date) {
            if (! $newUsers->has($date)) {
                $newUsers->put($date, 0);
            }
        }

        $filter = new Filter([
            'field_name' => 'customEvent:website_id',
            'string_filter' => new StringFilter([
                'match_type' => StringFilter\MatchType::EXACT,
                'value' => website_id(),
            ]),
        ]);

        $filterExpression = new FilterExpression([
            'filter' => $filter,
        ]);

        $response = Analytics::get(
            period: new Period(now()->subDays(8), now()->subDay()),
            metrics: ['activeUsers', 'screenPageViews', 'newUsers'],
            dimensions: ['date'],
            dimensionFilter: website()->isMainWebsite() ? null : $filterExpression,
        );

        $response = $response->sort(fn ($a, $b) => $a['date']->timestamp <=> $b['date']->timestamp);

        return [
            'labels' => $response->pluck('date')->map(
                function (Carbon $date) {
                    return $date->format('d/m/Y');
                }
            ),
            'datasets' => [
                [
                    'label' => __('core::translation.active_users'),
                    'data' => $response->pluck('activeUsers'),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('core::translation.page_views'),
                    'data' => $response->pluck('screenPageViews'),
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('core::translation.new_users'),
                    'data' => $response->pluck('newUsers'),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('core::translation.new_registed_users'),
                    'data' => $newUsers->values(),
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 1,
                ]
            ],
        ];
    }
}
