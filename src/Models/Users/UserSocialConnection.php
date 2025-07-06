<?php

namespace Juzaweb\Core\Models\Users;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Models\User;

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
