<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Notifications\Enums;

enum NotificationLogStatus: string
{
    case SENT = 'sent';
    case DELIVERED = 'delivered';
    case FAILED = 'failed';
    case PENDING = 'pending';
    case SENDING = 'sending';
}
