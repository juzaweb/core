<?php

namespace Juzaweb\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

/**
 * \Juzaweb\Core\Models\NotifySubscription
 *
 * @property int $id
 * @property string $channel
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $notifiable
 * @method static Builder|NotificationSubscription newModelQuery()
 * @method static Builder|NotificationSubscription newQuery()
 * @method static Builder|NotificationSubscription query()
 * @method static Builder|NotificationSubscription whereChannel($value)
 * @method static Builder|NotificationSubscription whereCreatedAt($value)
 * @method static Builder|NotificationSubscription whereData($value)
 * @method static Builder|NotificationSubscription whereId($value)
 * @method static Builder|NotificationSubscription whereNotifiableId($value)
 * @method static Builder|NotificationSubscription whereNotifiableType($value)
 * @method static Builder|NotificationSubscription whereUpdatedAt($value)
 * @mixin Eloquent
 */
class NotificationSubscription extends Model
{
    protected $table = 'notification_subscriptions';

    protected $fillable = [
        'channel',
        'notifiable_type',
        'notifiable_id',
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
