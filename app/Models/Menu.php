<?php

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Juzaweb\Core\Models\Menu
 *
 * @property int $id
 * @property string $name
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Juzaweb\Core\Models\Menu whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'content',
    ];
}
