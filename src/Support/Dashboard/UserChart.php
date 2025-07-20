<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support\Dashboard;

use Illuminate\Contracts\Support\Htmlable;
use Juzaweb\Core\Contracts\LineChart;
use Juzaweb\Core\Models\User;

class UserChart implements LineChart
{
    public function getTitle(): string
    {
        return __('New Users This Year');
    }

    public function getIcon(): string
    {
        return 'users';
    }

    public function getLabels(): array
    {
        return month_range(now()->startOfYear(), now()->endOfYear());
    }

    public function getData(): array
    {
        $data = User::cacheFor(3600)
            ->active()
            ->selectRaw('MONTH(created_at) as month, count(*) as total')
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->groupBy('month')
            ->pluck('total', 'month');

        for ($month = 1; $month <= 12; $month++) {
            if (!$data->has($month)) {
                $data->put($month, 0);
            }
        }

        return [
            [
                'label' => __('New Users'),
                'data' => $data->sortKeys()->values()->toArray(),
                'backgroundColor' => 'rgba(60,141,188,0.9)',
                'borderColor' => 'rgba(60,141,188,0.8)',
                'pointRadius' => false,
                'pointColor' => '#3b8bba',
                'pointStrokeColor' => 'rgba(60,141,188,1)',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(60,141,188,1)',
            ],
        ];
    }

    public function getColumnSize(): int
    {
        return 8;
    }

    public function render(): Htmlable
    {
        return view(
            'core::components.dashboard.line-chart',
            ['chart' => $this]
        );
    }
}
