<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;

class Guest extends Model
{
    use HasUuids, Notifiable;

    protected $table = 'guests';

    protected $fillable = [
        'ipv4',
        'ipv6',
        'user_agent',
        'name',
        'email',
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
