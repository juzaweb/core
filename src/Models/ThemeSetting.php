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
        $value = $this->attributes['value'] ?? null;

        if ($value === null) {
            return null;
        }

        // Optimistic JSON decode to avoid double parsing overhead
        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $value;
    }

    public function setValueAttribute($value): void
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        }

        $this->attributes['value'] = $value;
    }
}
