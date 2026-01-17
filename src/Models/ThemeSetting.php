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
        'website_id',
    ];

    public function getValueAttribute(): null|string|array
    {
        if (is_json($this->attributes['value'])) {
            return json_decode($this->attributes['value'], true, 512, JSON_THROW_ON_ERROR);
        }

        return $this->attributes['value'];
    }

    public function setValueAttribute($value): void
    {
        if (is_array($value)) {
            $value = json_encode($value, JSON_THROW_ON_ERROR);
        }

        $this->attributes['value'] = $value;
    }
}
