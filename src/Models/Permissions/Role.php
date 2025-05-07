<?php

namespace Juzaweb\Core\Models\Permissions;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Juzaweb\Core\Http\Resources\RoleResource;
use Juzaweb\Core\Permissions\Contracts\Role as RoleContract;
use Juzaweb\Core\Permissions\Exceptions\GuardDoesNotMatch;
use Juzaweb\Core\Permissions\Exceptions\RoleAlreadyExists;
use Juzaweb\Core\Permissions\Exceptions\RoleDoesNotExist;
use Juzaweb\Core\Permissions\Guard;
use Juzaweb\Core\Permissions\PermissionRegistrar;
use Juzaweb\Core\Permissions\Traits\HasPermissions;
use Juzaweb\Core\Permissions\Traits\RefreshesPermissionCache;
use Juzaweb\Core\Traits\HasAPI;
use App\Models\User;
use ReflectionException;

/**
 * Juzaweb\Core\Models\Permissions\Role
 *
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role permission(\Juzaweb\Core\Permissions\Contracts\Permission|Collection|array|string|int $permissions)
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereGuardName($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @method static Builder|Role api(array $params = [])
 * @method static Builder|Role filter(array $params)
 * @method static Builder|Role search(string $keyword)
 * @method static Builder|Role sort(array $params)
 * @property string $code
 * @property bool $grant_all_permissions
 * @method static Builder|Role whereCode($value)
 * @method static Builder|Role whereGrantAllPermissions($value)
 * @mixin Eloquent
 */
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
        return $this->hasWildcardPermission($permission, $this->getDefaultGuardName());

        // $permissionClass = $this->getPermissionClass();
        //
        // if (is_string($permission)) {
        //     $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        // }
        //
        // if (is_int($permission)) {
        //     $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        // }
        //
        // if (!$permission) {
        //     return false;
        // }
        //
        // if (!$this->getGuardNames()->contains($permission->guard_name)) {
        //     throw GuardDoesNotMatch::create($permission->guard_name, $this->getGuardNames());
        // }
        //
        // return $this->permissions->contains('id', $permission->id);
    }
}
