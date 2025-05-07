<?php

namespace Juzaweb\Core\Models;

/**
 *
 *
 * @property string $code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScopeGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScopeGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScopeGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScopeGroup whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScopeGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScopeGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScopeGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApiScopeGroup extends Model
{
    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $table = 'api_scope_groups';

    protected $fillable = ['code', 'name'];
}
