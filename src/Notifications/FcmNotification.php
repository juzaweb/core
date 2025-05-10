<?php

namespace Juzaweb\Core\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotificationResource;

class FcmNotification extends Notification
{
    public function __construct(protected string $title, protected string $body)
    {
        //
    }

    public function via($notifiable): array
    {
        return [FcmChannel::class];
    }

    public function toFcm($notifiable): FcmMessage
    {
        return (new FcmMessage)->notification(
            (new FcmNotificationResource())
            ->title($this->title)
            ->body($this->body)
        );
    }
}
