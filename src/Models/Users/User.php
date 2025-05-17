<?php

namespace Juzaweb\Core\Models\Users;

use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Juzaweb\Core\Facades\GlobalData;
use Juzaweb\Core\Models\Authenticatable;
use Juzaweb\Core\Models\Enums\UserStatus;
use Juzaweb\Core\Models\PasswordReset;
use Juzaweb\Core\Permissions\Traits\HasPermissions;
use Juzaweb\Core\Permissions\Traits\HasRoles;
use Juzaweb\Core\Traits\CausesActivity;
use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Core\Traits\HasPassportPasswordGrant;
use Juzaweb\Core\Traits\HasSocialConnection;
use Juzaweb\FileManager\Models\Media;
use Laravel\Passport\HasApiTokens;

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
        'random_password',
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

    public function scopeActive(Builder $builder): Builder
    {
        return $builder->where('status', UserStatus::ACTIVE);
    }

    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class, 'email', 'email');
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

        $roleAllPermissions = GlobalData::collect('roles')
            ->where('grantAllPermissions', true)
            ->pluck('key');

        return $this->hasRole($roleAllPermissions);
    }

    public function routeNotificationForFcm(Notification $notification): array|string|null
    {
        return $this->subscribedData('fcm', 'token');
    }

    public function getAvatarAttribute(): ?Media
    {
        if (! $this->relationLoaded('media')) {
            return null;
        }

        return $this->getFirstMedia('avatar');
    }
}
