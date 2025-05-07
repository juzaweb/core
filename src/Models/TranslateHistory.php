<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Core\Models\Enums\TranslateHistoryStatus;

class TranslateHistory extends Model
{
    protected $table = 'translate_histories';

    protected $fillable = [
        'translateable_type',
        'translateable_id',
        'locale',
        'status',
        'error',
    ];

    protected $casts = [
        'status' => TranslateHistoryStatus::class,
        'error' => 'array',
    ];

    public function translateable(): MorphTo
    {
        return $this->morphTo();
    }
}
