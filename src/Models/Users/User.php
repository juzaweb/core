<?php

namespace Juzaweb\Core\Models\Users;

use Eloquent;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as CollectionSupport;
use Illuminate\Support\Facades\Hash;
use Juzaweb\Core\Facades\GlobalData;
use Juzaweb\Core\Models\Authenticatable;
use Juzaweb\Core\Models\PasswordReset;
use Juzaweb\Core\Models\Permissions\Permission;
use Juzaweb\Core\Notifications\Traits\Subscriptable;
use Juzaweb\Core\Permissions\Contracts\Permission as PermissionContract;
use Juzaweb\Core\Permissions\Traits\HasPermissions;
use Juzaweb\Core\Permissions\Traits\HasRoles;
use Juzaweb\Core\Traits\CausesActivity;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\HasPassportPasswordGrant;
use Juzaweb\Core\Traits\HasSocialConnection;
use Database\Factories\UserFactory;
use Laravel\Passport\Client;
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\Token;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory,
        Notifiable,
        HasApiTokens,
        HasPermissions,
        HasRoles,
        HasAPI,
        HasSocialConnection,
        HasPassportPasswordGrant,
        HasUuids,
        CausesActivity;

    public static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public static function findByEmail(string $email): ?static
    {
        return static::whereEmail($email)->first();
    }

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('status', self::STATUS_ACTIVE);
    }

    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class, 'email', 'email');
    }

    public function hasPasswordReset(): bool
    {
        return $this->passwordResets()->exists();
    }

    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function hasPermission(): bool
    {
        if ($this->roles()->exists()) {
            return true;
        }

        return $this->permissions()->exists();
    }

    public function hasRoleAllPermissions(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $roleAllPermissions = GlobalData::collect('roles')
            ->where('grantAllPermissions', true)
            ->pluck('key');

        return $this->hasRole($roleAllPermissions);
    }

    public function routeNotificationForFcm(Notification $notification): array|string|null
    {
        return $this->subscribedData('fcm', 'token');
    }
}
