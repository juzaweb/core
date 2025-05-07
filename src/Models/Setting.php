<?php

namespace Juzaweb\Core\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;

/**
 * Juzaweb\Core\Models\Setting
 *
 * @property string $code
 * @property array|string|null $value
 * @property string|null $network_website_id
 * @method static Builder|Setting newModelQuery()
 * @method static Builder|Setting newQuery()
 * @method static Builder|Setting query()
 * @method static Builder|Setting whereCode($value)
 * @method static Builder|Setting whereNetworkWebsiteId($value)
 * @method static Builder|Setting whereValue($value)
 * @mixin Eloquent
 */
class Setting extends Model
{
    public const BOOLEAN_VALUES = ['1', 'true', 'false', '0', 0, 1, true, false];

    public $timestamps = false;

    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $table = 'settings';

    protected $fillable = [
        'code',
        'value',
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
