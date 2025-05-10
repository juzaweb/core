<?php

namespace Juzaweb\Core\Notifications\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Core\Models\NotificationSubscription;

/**
 * @mixin Model
 * @method static Builder|static hasSubscribedTo(array|string $channel)
 * @method static Builder|static orHasSubscribed(array|string $channel)
 */
trait Subscriptable
{
    /**
     * Boot the subscriptable trait for a model.
     */
    public static function bootSubscriptable(): void
    {
        static::deleting(
            function ($model) {
                $model->notificationSubscriptions()->delete();
            }
        );

        if (isset(static::$autoSubscribeChannels)) {
            static::created(
                function ($model) {
                    foreach (static::$autoSubscribeChannels as $channel) {
                        $model->subscribe($channel);
                    }
                }
            );
        }
    }

    /**
     * Retrieve the notification subscriptions for the current object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notificationSubscriptions(): MorphMany
    {
        return $this->morphMany(NotificationSubscription::class, 'notifiable', 'notifiable_type', 'notifiable_id');
    }

    /**
     * Scope a query to only include records that have subscribed to a specific channel.
     *
     * @param Builder $builder The query builder instance
     * @param array|string $channel The channel or array of channels to check for subscription
     * @return Builder
     */
    public function scopeHasSubscribedTo(Builder $builder, array|string $channel): Builder
    {
        if (is_string($channel)) {
            $channel = [$channel];
        }

        return $builder->whereHas(
            'notificationSubscriptions',
            function ($query) use ($channel) {
                $query->whereIn('channel', $channel);
            }
        );
    }

    /**
     * Scope a query to include records that have subscribed to a specific channel or channels.
     *
     * @param Builder $builder The query builder instance
     * @param array|string $channel The channel or array of channels to check for subscription
     * @return Builder
     */
    public function scopeOrHasSubscribedTo(Builder $builder, array|string $channel): Builder
    {
        if (is_string($channel)) {
            $channel = [$channel];
        }

        return $builder->orWhereHas(
            'notificationSubscriptions',
            function ($query) use ($channel) {
                $query->whereIn('channel', $channel);
            }
        );
    }

    /**
     * Retrieves the first notification subscription with the given channel.
     *
     * @param string $channel The channel to search for.
     * @return \Juzaweb\Core\Models\NotificationSubscription|null The first notification subscription with the given channel,
     * or null if not found.
     */
    public function subscribed(string $channel): ?NotificationSubscription
    {
        /** @var \Juzaweb\Core\Models\NotificationSubscription|null */
        return $this->notificationSubscriptions()->where('channel', $channel)->first();
    }

    /**
     * Retrieves the data associated with a subscribed channel.
     *
     * @param string $channel The channel to retrieve data for.
     * @return array The data associated with the subscribed channel, or an empty array if not subscribed.
     */
    public function subscribedData(string $channel, ?string $key = null, null|string|array $default = null): null|string|array
    {
        return $this->subscribed($channel)?->getData($key, $default);
    }

    /**
     * Deletes the subscription for the specified channel.
     *
     * @param string $channel The channel to unsubscribe from.
     * @return int The number of subscriptions deleted.
     */
    public function unsubscribe(string $channel): int
    {
        return $this->notificationSubscriptions()->where('channel', $channel)->delete();
    }

    /**
     * Deletes all subscriptions for the current object.
     *
     * @return int The number of subscriptions deleted.
     */
    public function unsubscribeAll(): int
    {
        return $this->notificationSubscriptions()->delete();
    }

    /**
     * Subscribes the current object to a given channel with optional data.
     *
     * @param string $channel The channel to subscribe to.
     * @param array $data Optional data to be associated with the subscription.
     * @param bool $force Whether to update the subscription if it already exists.
     * @return \Juzaweb\Core\Models\NotificationSubscription The created or updated subscription.
     */
    public function subscribe(string $channel, array $data = [], bool $force = false): NotificationSubscription
    {
        if ($subscription = $this->subscribed($channel)) {
            if ($force) {
                $subscription->data = $data;
            } else {
                $subscription->data = array_merge($subscription->data ?? [], $data);
            }

            $subscription->save();
            return $subscription;
        }

        /** @var \Juzaweb\Core\Models\NotificationSubscription */
        return $this->notificationSubscriptions()->create(
            [
                'channel' => $channel,
                'data' => $data,
            ]
        );
    }
}
