<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Cache;
use Juzaweb\Modules\Core\Models\DailyView;
use Juzaweb\Modules\Core\Models\PageViewHistory;

trait HasViews
{
    public function dailyViews(): MorphMany
    {
        return $this->morphMany(DailyView::class, 'viewable');
    }

    public function pageViewHistories(): MorphMany
    {
        return $this->morphMany(
            PageViewHistory::class,
            'viewable'
        );
    }

    public function getViewPageToken(): string
    {
        return base64url_encode(encrypt([static::class, $this->id]));
    }

    public function incrementViews($viewer, ?string $ip, int $count = 1): void
    {
        if (!$ip) {
            return;
        }

        $rememberKey = cache_prefix(
            hash('sha256', sprintf(
                'viewed_%s_%s_%s',
                str_replace('\\', '_', get_class($this)),
                $this->id,
                $ip
            ))
        );

        Cache::remember($rememberKey, 3600, function () use ($count, $viewer) {
            $date = now()->toDateString();
            $dailyView = $this->dailyViews()
                ->where('date', $date)
                ->first();

            Model::withoutEvents(function () use ($count) {
                $this->increment('views', $count);
            });

            if ($dailyView) {
                $dailyView->increment('views', $count);
            } else {
                $this->dailyViews()->create([
                    'date' => $date,
                    'views' => $count,
                ]);
            }

            $this->pageViewHistories()->firstOrCreate([
                'viewer_id' => $viewer->id,
                'viewer_type' => get_class($viewer),
            ]);

            return true;
        });
    }
}
