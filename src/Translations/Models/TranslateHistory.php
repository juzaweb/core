<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Modules\Core\Translations\Enums\TranslateHistoryStatus;

class TranslateHistory extends Model
{
    protected $table = 'translate_histories';

    protected $fillable = [
        'translateable_type',
        'translateable_id',
        'locale',
        'status',
        'error',
        'new_model_id',
        'new_model_type',
    ];

    protected $casts = [
        'status' => TranslateHistoryStatus::class,
        'error' => 'array',
    ];

    public function translateable(): MorphTo
    {
        return $this->morphTo();
    }

    public function markAsFailed(null|array|string $message)
    {
        if (is_string($message)) {
            $message = ['message' => $message];
        }

        $this?->update([
            'status' => TranslateHistoryStatus::FAILED,
            'error' => $message,
        ]);
    }
}
