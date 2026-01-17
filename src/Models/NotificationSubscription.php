<?php

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;

class NotificationSubscription extends Model
{
    use HasUuids;

    protected $table = 'notification_subscriptions';

    protected $fillable = [
        'channel',
        'notifiable_type',
        'notifiable_id',
        'website_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'notifiable_type', 'notifiable_id');
    }

    public function getData(?string $key, null|string|array $default = null): null|string|array
    {
        if (is_null($key)) {
            return $this->data;
        }

        return Arr::get($this->data ?? [], $key, $default);
    }
}
