<?php

namespace Juzaweb\Core\Models;

/**
 * Juzaweb\Core\Models\Languages
 *
 * @property int $id
 * @property string $key
 * @property string $name
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $default
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Languages whereDefault($value)
 */
class Languages extends Model
{
    protected $table = 'languages';
    protected $primaryKey = 'id';
    protected $fillable = [
        'key',
        'name',
        'status'
    ];
}
