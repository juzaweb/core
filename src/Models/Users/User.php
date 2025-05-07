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

/**
 * Juzaweb\Core\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $uuid
 * @property-read Collection<int, Client> $clients
 * @property-read int|null $clients_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Token> $tokens
 * @property-read int|null $tokens_count
 * @property-read bool $is_super_admin
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUuid($value)
 * @method static Builder|User permission(PermissionContract|CollectionSupport|array|string|int $permissions)
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property string|null $birthday
 * @property string $status
 * @property-read Collection<int, \Juzaweb\Core\Models\PasswordReset> $passwordResets
 * @property-read int|null $password_resets_count
 * @property-read Collection<int, \Juzaweb\Core\Models\Permissions\Role> $roles
 * @property-read int|null $roles_count
 * @method static Builder|User api(array $params = [])
 * @method static Builder|User filter(array $params)
 * @method static Builder|User role(\Juzaweb\Core\Permissions\Contracts\Role|\Illuminate\Support\Collection|array|string|int $roles, ?string $guard = null)
 * @method static Builder|User search(string $keyword)
 * @method static Builder|User sort(array $params)
 * @method static Builder|User whereBirthday($value)
 * @method static Builder|User whereIsSuperAdmin($value)
 * @method static Builder|User whereStatus($value)
 * @property-read Collection<int, \Juzaweb\Core\Models\Users\UserSocialConnection> $socialConnections
 * @property-read int|null $social_connections_count
 * @property-read Collection<int, \Juzaweb\Core\Models\Activity> $actions
 * @property-read int|null $actions_count
 * @property-read Collection<int, \Juzaweb\Core\Models\NotificationSubscription> $notificationSubscriptions
 * @property-read int|null $notification_subscriptions_count
 * @method static Builder|User active()
 * @method static Builder|User hasSubscribedTo(array|string $channel)
 * @method static Builder|User orHasSubscribedTo(array|string $channel)
 * @mixin Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    use HasFactory,
        Notifiable,
        HasApiTokens,
        HasPermissions,
        HasRoles,
        HasAPI,
        HasSocialConnection,
        HasPassportPasswordGrant,
        HasUuids,
        Subscriptable,
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

    public function validateForPassportPasswordGrant(string $password): bool
    {
        if (Hash::check($password, $this->password)) {
            return true;
        }

        // For create token with social login
        if (Hash::check($this->password, $password)) {
            return true;
        }

        return false;
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
