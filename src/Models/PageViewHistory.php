<?php

namespace Juzaweb\Modules\Core\Models;

class PageViewHistory extends Model
{

    protected $table = 'page_view_histories';

    protected $fillable = [
        'viewable_id',
        'viewable_type',
        'viewer_id',
        'viewer_type',
    ];

    public function viewable()
    {
        return $this->morphTo();
    }

    public function viewer()
    {
        return $this->morphTo();
    }
}
