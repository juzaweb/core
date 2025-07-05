<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Models;

use Illuminate\Notifications\DatabaseNotification;
use Juzaweb\Core\Http\Resources\NotificationResource;
use Juzaweb\Core\Traits\HasAPI;

class Notification extends DatabaseNotification
{
    use HasAPI;

    protected $table = 'notifications';

    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'title',
    ];

    public $filterable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'read_at',
    ];

    protected $searchable = [
        'title',
    ];

    public static function getResource(): string
    {
        return NotificationResource::class;
    }
}
