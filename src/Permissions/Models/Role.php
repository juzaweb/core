<?php

namespace Juzaweb\Modules\Core\Permissions\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Juzaweb\Modules\Core\Http\Resources\RoleResource;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Permissions\Contracts\Role as RoleContract;
use Juzaweb\Modules\Core\Permissions\Exceptions\GuardDoesNotMatch;
use Juzaweb\Modules\Core\Permissions\Exceptions\RoleAlreadyExists;
use Juzaweb\Modules\Core\Permissions\Exceptions\RoleDoesNotExist;
use Juzaweb\Modules\Core\Permissions\Guard;
use Juzaweb\Modules\Core\Permissions\PermissionRegistrar;
use Juzaweb\Modules\Core\Permissions\Traits\HasPermissions;
use Juzaweb\Modules\Core\Permissions\Traits\RefreshesPermissionCache;
use Juzaweb\Modules\Core\Traits\HasAPI;
use ReflectionException;

class Role extends Model implements RoleContract
{
    use HasPermissions, RefreshesPermissionCache, HasAPI;

    protected $table = 'roles';

    protected $guarded = [];

    protected $searchable = ['name'];

    protected $sortable = ['name', 'created_at'];

    protected $casts = ['grant_all_permissions' => 'boolean'];

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? config('auth.defaults.guard');

        parent::__construct($attributes);

        $this->guarded[] = $this->primaryKey;
    }

    public static function getResource(): string
    {
        return RoleResource::class;
    }

    /**
     * @param  array  $attributes
     * @return Role
     * @throws ReflectionException
     */
    public static function create(array $attributes = []): Model|Builder
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $params = ['code' => $attributes['code'], 'guard_name' => $attributes['guard_name']];

        if (static::findByParam($params)) {
            throw RoleAlreadyExists::create($attributes['code'], $attributes['guard_name']);
        }

        return static::query()->create($attributes);
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            PermissionRegistrar::$pivotRole,
            PermissionRegistrar::$pivotPermission
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            model_of_guard($this->attributes['guard_name']),
            'model',
            'model_has_roles',
            PermissionRegistrar::$pivotRole,
            'model_id'
        );
    }

    /**
     * Find a role by its code and guard name.
     *
     * @param  string  $code
     * @param  null  $guardName
     *
     * @return RoleContract
     *
     * @throws ReflectionException
     */
    public static function findByCode(string $code, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['code' => $code, 'guard_name' => $guardName]);

        if (!$role) {
            throw RoleDoesNotExist::coded($code);
        }

        return $role;
    }

    /**
     * Find a role by its name and guard name.
     *
     * @param  string  $name
     * @param  null  $guardName
     *
     * @return RoleContract
     *
     * @throws ReflectionException
     */
    public static function findByName(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$role) {
            throw RoleDoesNotExist::named($name);
        }

        return $role;
    }

    /**
     * Find a role by its id (and optionally guardName).
     *
     * @param  int  $id
     * @param  null  $guardName
     *
     * @return RoleContract
     * @throws ReflectionException
     */
    public static function findById(int $id, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam([(new static())->getKeyName() => $id, 'guard_name' => $guardName]);

        if (!$role) {
            throw RoleDoesNotExist::withId($id);
        }

        return $role;
    }

    /**
     * Find or create role by its name (and optionally guardName).
     *
     * @param  string  $name
     * @param  null  $guardName
     *
     * @return RoleContract
     * @throws ReflectionException
     */
    public static function findOrCreate(string $name, $guardName = null): RoleContract
    {
        $guardName = $guardName ?? Guard::getDefaultName(static::class);

        $role = static::findByParam(['name' => $name, 'guard_name' => $guardName]);

        if (!$role) {
            /** @var RoleContract */
            return static::query()
                ->create(
                    [
                        'name' => $name,
                        'guard_name' => $guardName
                    ]
                );
        }

        return $role;
    }

    protected static function findByParam(array $params = []): Model|Role|null
    {
        $query = static::query();

        foreach ($params as $key => $value) {
            $query->where($key, $value);
        }

        return $query->first();
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  string|Permission  $permission
     *
     * @return bool
     *
     * @throws GuardDoesNotMatch
     */
    public function hasPermissionTo($permission): bool
    {
        // return $this->hasWildcardPermission($permission, $this->getDefaultGuardName());

        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByCode($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        if (!$permission) {
            return false;
        }

        if (!$this->getGuardNames()->contains($permission->guard_name)) {
            throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        }

        return $this->permissions->contains('id', $permission->id);
    }
}
