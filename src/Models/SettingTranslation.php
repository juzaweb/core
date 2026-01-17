<?php

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SettingTranslation extends Model
{
    protected $table = 'setting_translations';

    protected $fillable = [
        'setting_code',
        'locale',
        'lang_value',
        'setting_id',
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(Setting::class, 'setting_id', 'id');
    }
}
