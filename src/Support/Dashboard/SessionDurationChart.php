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
use function Juzaweb\Modules\Admin\Support\Dashboard\website;
use function Juzaweb\Modules\Admin\Support\Dashboard\website_id;

class SessionDurationChart extends LineChart
{
    public string $id = 'session-duration';

    public function getTitle(): string
    {
        return __('core::translation.average_session_duration');
    }

    public function getIcon(): string
    {
        return 'clock';
    }

    public function getData(): array
    {
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
            metrics: ['averageSessionDuration', 'userEngagementDuration'],
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
                    'label' => __('core::translation.avg_session_duration_seconds'),
                    'data' => $response->pluck('averageSessionDuration'),
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => __('core::translation.user_engagement_duration_seconds'),
                    'data' => $response->pluck('userEngagementDuration'),
                    'backgroundColor' => 'rgba(255, 159, 64, 0.2)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
