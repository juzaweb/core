<?php

namespace Juzaweb\Modules\Core\Models;

class DailyView extends Model
{
    protected $table = 'daily_views';

    protected $fillable = [
        'viewable_type',
        'viewable_id',
        'date',
        'views',
    ];
}
