<?php

namespace Juzaweb\Core\Models\Users;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Juzaweb\Core\Models\UserMeta
 *
 * @property int $id
 * @property string $user_id
 * @property string $meta_key
 * @property string|null $meta_value
 * @property-read User $user
 * @method static Builder|UserMeta newModelQuery()
 * @method static Builder|UserMeta newQuery()
 * @method static Builder|UserMeta query()
 * @method static Builder|UserMeta whereId($value)
 * @method static Builder|UserMeta whereMetaKey($value)
 * @method static Builder|UserMeta whereMetaValue($value)
 * @method static Builder|UserMeta whereUserId($value)
 * @mixin Eloquent
 */
class UserMeta extends Model
{
    use HasFactory;

    protected $table = 'user_metas';

    protected $fillable = [
        'user_id',
        'meta_key',
        'meta_value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
