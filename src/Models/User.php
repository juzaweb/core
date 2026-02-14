<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Juzaweb\Modules\Admin\Database\Factories\UserFactory;
use Juzaweb\Modules\Admin\Enums\UserStatus;
use Juzaweb\Modules\Core\FileManager\Traits\HasMedia;
use Juzaweb\Modules\Core\Permissions\Models\Role;
use Juzaweb\Modules\Core\Permissions\Traits\HasPermissions;
use Juzaweb\Modules\Core\Permissions\Traits\HasRoles;
use Juzaweb\Modules\Core\Traits\CausesActivity;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\HasSocialConnection;
use Juzaweb\QueryCache\QueryCacheable;

class User extends Authenticatable implements MustVerifyEmail
{
    use CausesActivity,
        HasAPI,
        HasFactory,
        HasPermissions,
        HasRoles,
        HasSocialConnection,
        HasUuids,
        Notifiable,
        QueryCacheable,
        HasMedia;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'status',
        'is_super_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_super_admin',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'is_super_admin' => 'boolean',
        'status' => UserStatus::class,
    ];

    protected $searchable = ['name', 'email'];

    protected $filterable = ['status', 'is_super_admin'];

    protected $sortable = ['id', 'name', 'email', 'status'];

    protected $sortDefault = ['id' => 'DESC'];

    public $mediaChannels = ['avatar'];

    protected $appends = [
        'avatar',
    ];

    public static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public static function findByEmail(string $email): ?static
    {
        return static::whereEmail($email)->first();
    }

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('status', UserStatus::ACTIVE);
    }

    public function getAvatarAttribute(): ?string
    {
        if (! $this->relationLoaded('media')) {
            return null;
        }

        return $this->getAvatarUrl();
    }

    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class, 'email', 'email');
    }

    public function getAvatarUrl(int $size = 32): string
    {
        return $this->getFirstMedia('avatar')?->url
            ?? "https://1.gravatar.com/avatar/". md5($this->email) ."?s={$size}&d=mm&r=g";
    }

    public function hasPasswordReset(): bool
    {
        return $this->passwordResets()->exists();
    }

    public function isActive(): bool
    {
        return $this->status === UserStatus::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->status === UserStatus::INACTIVE;
    }

    public function isBanned(): bool
    {
        return $this->status === UserStatus::BANNED;
    }

    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    public function hasPermission(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

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

        $roleAllPermissions = Role::cacheFor(3600)
            ->where('grant_all_permissions', true)
            ->pluck('code');

        return $this->hasRole($roleAllPermissions);
    }

    public function routeNotificationForFcm(Notification $notification): array|string|null
    {
        return $this->subscribedData('fcm', 'token');
    }
}
