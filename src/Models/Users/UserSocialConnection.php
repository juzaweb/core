<?php

namespace Juzaweb\Core\Models\Users;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Models\User;

/**
 * Juzaweb\Core\Models\Users\UserSocialConnection
 *
 * @property int $id
 * @property string $user_id
 * @property string $provider
 * @property string $provider_id
 * @property array $provider_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection whereProviderData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserSocialConnection whereUserId($value)
 * @mixin \Eloquent
 */
class UserSocialConnection extends Model
{
    protected $table = 'user_social_connections';

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_data',
        'scopes',
    ];

    protected $casts = [
        'provider_data' => 'array',
        'scopes' => 'array',
    ];

    public static function findByProvider(string $provider, string $providerId): ?static
    {
        return self::where('provider', $provider)->where('provider_id', $providerId)->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
