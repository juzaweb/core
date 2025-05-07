<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Models\Users;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Notifications\Traits\Subscriptable;

/**
 *
 *
 * @property string $id
 * @property string $ipv4
 * @property string|null $ipv6
 * @property string|null $user_agent
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Juzaweb\Core\Models\NotificationSubscription> $notificationSubscriptions
 * @property-read int|null $notification_subscriptions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Guest hasSubscribedTo(array|string $channel)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest orHasSubscribedTo(array|string $channel)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereIpv4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereIpv6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guest whereUserAgent($value)
 * @mixin \Eloquent
 */
class Guest extends Model
{
    use HasUuids, Notifiable, Subscriptable;

    protected $table = 'guests';

    protected $fillable = [
        'ipv4',
        'ipv6',
        'user_agent',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
