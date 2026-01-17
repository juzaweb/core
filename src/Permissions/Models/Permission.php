<?php

namespace Juzaweb\Modules\Core\Permissions\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Juzaweb\Modules\Core\Http\Resources\PermissionResource;
use Juzaweb\Modules\Core\Permissions\Contracts\Permission as PermissionContract;
use Juzaweb\Modules\Core\Permissions\Exceptions\PermissionAlreadyExists;
use Juzaweb\Modules\Core\Permissions\Exceptions\PermissionDoesNotExist;
use Juzaweb\Modules\Core\Permissions\Guard;
use Juzaweb\Modules\Core\Permissions\PermissionRegistrar;
use Juzaweb\Modules\Core\Permissions\Traits\HasRoles;
use Juzaweb\Modules\Core\Permissions\Traits\RefreshesPermissionCache;
use Juzaweb\Modules\Core\Traits\HasAPI;
use ReflectionException;

class Permission extends Model implements PermissionContract
{
    use HasRoles, RefreshesPermissionCache, HasAPI;

    protected $table = 'permissions';

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    public static function getResource(): string
    {
        return PermissionResource::class;
    }

    public static function create(array $attributes = []): Model|Builder
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission(['name' => $attributes['name'], 'guard_name' => $attributes['guard_name']]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group', 'code');
    }

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_has_permissions',
            PermissionRegistrar::$pivotPermission,
            PermissionRegistrar::$pivotRole
        );
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            model_of_guard($this->attributes['guard_name']),
            'model',
            'model_has_permissions',
            PermissionRegistrar::$pivotPermission,
            'model_id'
        );
    }

    /**
     * Find a permission by its name (and optionally guardName).
     *
     * @param  string  $name
     * @param  string|null  $guardName
     *
     * @return PermissionContract
     * @throws PermissionDoesNotExist|ReflectionException
     *
     */
    public static function findByName(string $name, $guardName = null): ?PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);
        if (!$permission) {
            return null;
        }

        return $permission;
    }

    /**
     * Find a permission by its code (and optionally guardName).
     *
     * @param  string  $name
     * @param  string|null  $guardName
     *
     * @return PermissionContract
     * @throws PermissionDoesNotExist|ReflectionException
     *
     */
    public static function findByCode(string $name, ?string $guardName = null): ?PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['code' => $name, 'guard_name' => $guardName]);

        if (!$permission) {
            return null;
        }

        return $permission;
    }

    /**
     * Find a permission by its id (and optionally guardName).
     *
     * @param  int  $id
     * @param  string|null  $guardName
     *
     * @return PermissionContract
     * @throws PermissionDoesNotExist|ReflectionException
     *
     */
    public static function findById(int $id, $guardName = null): ?PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission([(new static())->getKeyName() => $id, 'guard_name' => $guardName]);

        if (!$permission) {
            return null;
        }

        return $permission;
    }

    /**
     * Find or create permission by its name (and optionally guardName).
     *
     * @param  string  $name
     * @param  string|null  $guardName
     *
     * @return Builder|Model|PermissionContract
     * @throws ReflectionException
     */
    public static function findOrCreate(string $name, $guardName = null): PermissionContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);
        $permission = static::getPermission(['name' => $name, 'guard_name' => $guardName]);

        if (!$permission) {
            return static::query()->create(['name' => $name, 'guard_name' => $guardName]);
        }

        return $permission;
    }

    /**
     * Get the current cached permissions.
     *
     * @param  array  $params
     * @param  bool  $onlyOne
     *
     * @return Collection
     */
    protected static function getPermissions(array $params = [], bool $onlyOne = false): Collection
    {
        return app(PermissionRegistrar::class)
            ->setPermissionClass(static::class)
            ->getPermissions($params, $onlyOne);
    }

    /**
     * Get the current cached first permission.
     *
     * @param  array  $params
     *
     * @return PermissionContract
     */
    protected static function getPermission(array $params = []): ?PermissionContract
    {
        return static::getPermissions($params, true)->first();
    }
}
