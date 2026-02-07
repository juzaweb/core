<?php

namespace Juzaweb\Modules\Core\Models;

use Juzaweb\Modules\Core\Traits\HasThemeField;

class ThemeSetting extends Model
{
    use HasThemeField;

    public $timestamps = false;

    protected $table = 'theme_settings';

    protected $fillable = [
        'code',
        'theme',
        'value',
    ];

    public function getValueAttribute(): null|string|array
    {
        $value = $this->attributes['value'];

        // Optimistic JSON decode
        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return $value;
        }
    }

    public function setValueAttribute($value): void
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        }

        $this->attributes['value'] = $value;
    }
}
