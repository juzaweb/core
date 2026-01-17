<?php

namespace Juzaweb\Modules\Core\Enums;

enum NotificationTypeEnum: string
{
    case INACTIVE_ADMIN = 'inactive_admin';
    case PAYMENT_REMINDER = 'payment_reminder';
    case SECURITY_ALERT = 'security_alert';
    case SYSTEM_UPDATE = 'system_update';

    public function label(): string
    {
        return match ($this) {
            self::INACTIVE_ADMIN => 'Inactive Admin Reminder',
            self::PAYMENT_REMINDER => 'Payment Reminder',
            self::SECURITY_ALERT => 'Security Alert',
            self::SYSTEM_UPDATE => 'System Update',
        };
    }
}
