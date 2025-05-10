<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Juzaweb\Core\PageBuilder\Elements\AnalyticCard;
use Juzaweb\Core\PageBuilder\Elements\Charts\BarChart;
use Juzaweb\Core\PageBuilder\Elements\Charts\LineChart;
use Juzaweb\Core\PageBuilder\Elements\Charts\PieChart;
use Juzaweb\Core\PageBuilder\Elements\Grids\Col12;
use App\Models\User;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class Analytic
{
    public static function visitorCard(): Col12
    {
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $yearly = 0;
        $old = 0;

        if (dashboard_analytics_chart_enabled()) {
            $yearly = Analytics::fetchTotalVisitorsAndPageViews(Period::create($startDate, $endDate))
                ->sum('activeUsers');
            $old = Analytics::fetchTotalVisitorsAndPageViews(Period::create($startDate->subDays(7), $startDate))
                ->sum('activeUsers');
        }

        return Col12::make()
            ->attributes(['item' => true, 'xs' => 12, 'sm' => 6, 'md' => 4, 'lg' => 3])
            ->add(
                AnalyticCard::make([
                    'title' => __('Total Visitors'),
                    'count' => $yearly,
                    'percentage' => min($yearly / ($old ?: 1) * 100, 100),
                    'isLoss' => false,
                    'extra' => __('Visitors'),
                ])
            );
    }

    public static function pageViewCard(): Col12
    {
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $yearlyPageViews = 0;
        $oldPageViews = 0;

        if (dashboard_analytics_chart_enabled()) {
            $yearlyPageViews = Analytics::fetchTotalVisitorsAndPageViews(Period::create($startDate, $endDate))
                ->sum('screenPageViews');
            $oldPageViews = Analytics::fetchTotalVisitorsAndPageViews(Period::create($startDate->subDays(7), $startDate))
                ->sum('screenPageViews');
        }

        return Col12::make()
            ->attributes(['item' => true, 'xs' => 12, 'sm' => 6, 'md' => 4, 'lg' => 3])
            ->add(
                AnalyticCard::make([
                    'title' => __('Total Page Views'),
                    'count' => $yearlyPageViews,
                    'percentage' => min($yearlyPageViews / ($oldPageViews ?: 1) * 100, 100),
                    'isLoss' => false,
                    'extra' => __('Page Views'),
                ])
            );
    }

    public static function userCard(): Col12
    {
        $startDate = Carbon::now()->subDays(7);

        $totalUsers = User::cacheFor(3600)
            ->whereDate('created_at', '>=', $startDate)
            ->count();

        $oldUsers = User::cacheFor(3600)
            ->whereDate('created_at', '<', $startDate)
            ->count();

        return Col12::make()
            ->attributes(['item' => true, 'xs' => 12, 'sm' => 6, 'md' => 4, 'lg' => 3])
            ->add(
                AnalyticCard::make([
                    'title' => __('Total Users'),
                    'count' => User::active()->count(),
                    'percentage' => min($totalUsers / ($oldUsers ?: 1) * 100, 100),
                    'isLoss' => false,
                    'extra' => __('Total Users'),
                ])
            );
    }

    public static function returningCard(): Col12
    {
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $yearly = 0;
        $oldYearly = 0;
        if (dashboard_analytics_chart_enabled()) {
            $yearly = Analytics::fetchUserTypes(Period::create($startDate, $endDate))
                ->where('newVsReturning', 'returning')
                ->first()['activeUsers'] ?? 0;
            $oldYearly = Analytics::fetchUserTypes(Period::create($startDate->subDays(7), $startDate))
                ->where('newVsReturning', 'returning')
                ->first()['activeUsers'] ?? 0;
        }

        return Col12::make()
            ->attributes(['item' => true, 'xs' => 12, 'sm' => 6, 'md' => 4, 'lg' => 3])
            ->add(
                AnalyticCard::make([
                    'title' => __('Returning'),
                    'count' => $yearly,
                    'percentage' => min($yearly / ($oldYearly ?: 1) * 100, 100),
                    'isLoss' => false,
                    'extra' => __('Returning'),
                ])
            );
    }

    public static function userChart(): LineChart
    {
        $dates = month_range(now()->subYears(), now());
        $users = User::cacheFor(3600)
            ->whereDate(
                'created_at',
                '>=',
                now()->subYears()
            )
            ->whereDate('created_at', '<=', now())
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->get([DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('count(*) as total')])
            ->pluck('total', 'month');

        return LineChart::make(['title' => __('New Users By Month')])->xAxis(
            [
                [
                    'scaleType' => 'point',
                    'id' => __('Date'),
                    'data' => $dates,
                ]
            ]
        )
            ->series(
                [
                    [
                        'data' => array_map(fn($date) => $users->get($date), $dates),
                        'area' => true,
                    ]
                ]
            );
    }

    public static function visitorChart(): LineChart
    {
        $dates = date_range(now()->subDays(7), now());

        $analytics = Analytics::fetchTotalVisitorsAndPageViews(Period::create(now()->subDays(7), now()))
            ->keyBy(fn ($item) => $item['date']->format('Y-m-d'));

        return LineChart::make(['title' => __('Unique Visitor')])->xAxis(
            [
                [
                    'scaleType' => 'point',
                    'id' => __('Date'),
                    'data' => $dates,
                ]
            ]
        )
            ->series(
                [
                    [
                        'data' => array_map(
                            fn($date) => $analytics->get($date)['screenPageViews'] ?? 0, $dates
                        ),
                        'area' => true,
                        'label' => __('Page Views'),
                    ],
                    [
                        'data' => array_map(
                            fn($date) => $analytics->get($date)['activeUsers'] ?? 0, $dates
                        ),
                        'area' => true,
                        'label' => __('Visitors'),
                    ]
                ]
            );
    }

    public static function topCountriesChart(): PieChart
    {
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $analytics = Analytics::fetchTopCountries(Period::create($startDate, $endDate));
        $total = $analytics->sum('screenPageViews');

        return PieChart::make(['title' => __('Top Countries (%)')])
            ->slotProps(['legend' => ['hidden' => true]])
            ->height(300)
            ->margin(['right' => 5])
            ->series(
                [
                    [
                        'data' => $analytics->map(
                            fn($data) => [
                                'label' => $data['country'],
                                'value' => $total > 0 ? round($data['screenPageViews'] / $total * 100, 2) : 0,
                            ]
                        ),
                    ]
                ]
            );
    }

    public static function mostVisitedPagesChart(): BarChart
    {
        $excludePages = config('core.dashboard_analytics_exclude_pages', []);
        $analytics = Analytics::fetchMostVisitedPages(Period::create(now()->subDays(7), now()), 10)
            ->groupBy('fullPageUrl')
            ->map(
                function ($items) {
                    return $items->map(
                        function ($item) use ($items) {
                            $item['screenPageViews'] = $items->sum('screenPageViews');

                            return $item;
                        }
                    )->first();
                }
            )
            ->filter(
                fn ($item) => !Str::is(
                    $excludePages,
                    parse_url("https://{$item['fullPageUrl']}", PHP_URL_PATH)
                )
            )
            ->sortByDesc('screenPageViews')
            ->values();

        return BarChart::make(['title' => __('Most Visited Pages')])
            ->yAxis([['scaleType' => 'band', 'data' => $analytics->pluck('pageTitle')]])
            ->xAxis([['label' => __('Page Views')]])
            ->layout('horizontal')
            //->margin(['left' => 200])
            ->series(
                [
                    [
                        'data' => $analytics->map(
                            fn($data) => $data['screenPageViews']
                        ),
                    ]
                ]
            );
    }

    public static function topReferrersChart(): BarChart
    {
        $analytics = Analytics::fetchTopReferrers(
            Period::create(now()->subDays(7), now()),
            10
        );

        return BarChart::make(['title' => __('Top Referrers')])
            ->yAxis([[
                'scaleType' => 'band',
                'data' => $analytics->map(
                    function ($item) {
                        if (empty($item['pageReferrer'])) {
                            $item['pageReferrer'] = __('Direct');
                        }

                        return $item;
                    }
                )->pluck('pageReferrer')
            ]])
            ->xAxis([['label' => __('Page Views')]])
            ->layout('horizontal')
            //->margin(['left' => 200])
            ->series(
                [
                    [
                        'data' => $analytics->map(
                            fn($data) => $data['screenPageViews']
                        ),
                    ]
                ]
            );
    }

    public static function visitorTypesChart(): PieChart
    {
        $startDate = Carbon::now()->subWeek();
        $endDate = Carbon::now();

        $analytics = Analytics::fetchUserTypes(Period::create($startDate, $endDate));
        $total = $analytics->sum('activeUsers');

        return PieChart::make(['title' => __('Visitor Types (%)')])
            ->slotProps(['legend' => ['hidden' => true]])
            ->height(300)
            ->margin(['right' => 5])
            ->series(
                [
                    [
                        'data' => [
                            [
                                'label' => __('New Visitor'),
                                'value' => $total > 0
                                    ? round($analytics->where('newVsReturning', 'new')
                                            ->sum('activeUsers') / $total * 100, 2)
                                    : 0,
                            ],
                            [
                                'label' => __('Returning Visitor'),
                                'value' => $total > 0
                                    ? round($analytics->where('newVsReturning', 'returning')
                                            ->sum('activeUsers') / $total * 100, 2)
                                    : 0,
                            ]
                        ],
                    ]
                ]
            );
    }
}
