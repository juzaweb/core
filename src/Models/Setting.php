<?php

namespace Juzaweb\Modules\Core\Models;

use Juzaweb\Modules\Core\Traits\Translatable;

class Setting extends Model
{
    use Translatable;

    public const BOOLEAN_VALUES = ['1', 'true', 'false', '0', 0, 1, true, false];

    public $timestamps = false;

    protected $table = 'settings';

    protected $fillable = [
        'code',
        'value',
        'translatable',
        'website_id',
    ];

    public $translatedAttributes = [
        'lang_value',
        'locale',
    ];

    protected $casts = [
        'translatable' => 'boolean',
    ];

    public function getValueAttribute(): null|string|array
    {
        if ($this->translatable) {
            return $this->getTranslation()?->lang_value;
        }

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
