<?php

namespace Juzaweb\Core\Models;

/**
 *
 *
 * @property string $code
 * @property string $name
 * @property string $group_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Juzaweb\Core\Models\ApiScopeGroup|null $group
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope query()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope whereGroupCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ApiScope whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApiScope extends Model
{
    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $table = 'api_scopes';

    protected $fillable = ['code', 'name'];

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ApiScopeGroup::class, 'group_code', 'code');
    }
}
