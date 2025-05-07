<?php

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Core\Notifications\Enums\NotificationLogStatus;

class NotificationLog extends Model
{
    use HasUuids;

    protected $table = 'notification_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel',
        'notifiable_type',
        'notifiable_id',
        'notification_type',
        'extra',
        'sent_at',
        'delivered_at',
        'failed_data',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'extra' => 'array',
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'failed_data' => 'array',
        'status' => NotificationLogStatus::class,
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'notifiable_type', 'notifiable_id');
    }

    public function scopeSent(Builder $builder): Builder
    {
        return $builder->whereNotNull('sent_at');
    }

    public function scopeDelivered(Builder $builder): Builder
    {
        return $builder->whereNotNull('delivered_at');
    }

    public function scopeFailed(Builder $builder): Builder
    {
        return $builder->where('status', NotificationLogStatus::FAILED);
    }

    public function markAsSent(): bool
    {
        return $this->update(['status' => NotificationLogStatus::SENT, 'sent_at' => now()]);
    }

    public function markAsFailed(array $data = []): bool
    {
        return $this->update(['status' => NotificationLogStatus::FAILED, 'sent_at' => now(), 'failed_data' => $data]);
    }

    public function markAsDelivered(): bool
    {
        return $this->update(['status' => NotificationLogStatus::DELIVERED, 'delivered_at' => now()]);
    }

    public function getExtra(?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return $this->extra;
        }

        return data_get($this->extra, $key, $default);
    }
}
