<?php

namespace Juzaweb\Core\Permissions\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\Core\Permissions\Exceptions\GuardDoesNotMatch;
use Juzaweb\Core\Permissions\Exceptions\PermissionDoesNotExist;
use Juzaweb\Core\Permissions\Exceptions\WildcardPermissionInvalidArgument;
use Juzaweb\Core\Permissions\Guard;
use Juzaweb\Core\Permissions\PermissionRegistrar;
use Juzaweb\Core\Permissions\Contracts\Permission;
use Juzaweb\Core\Permissions\Contracts\Role;
use Juzaweb\Core\Permissions\WildcardPermission;

trait HasPermissions
{
    /** @var Permission */
    private Permission $permissionClass;

    public static function bootHasPermissions(): void
    {
        static::deleting(
            function ($model) {
                if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                    return;
                }

                $model->permissions()->detach();
            }
        );
    }

    public function getPermissionClass(): Permission
    {
        if (! isset($this->permissionClass)) {
            $this->permissionClass = app(PermissionRegistrar::class)->getPermissionClass();
        }

        return $this->permissionClass;
    }

    /**
     * A model may have multiple direct permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->morphToMany(
            \Juzaweb\Core\Models\Permissions\Permission::class,
            'model',
            'model_has_permissions',
            'model_id',
            PermissionRegistrar::$pivotPermission
        );
    }

    /**
     * Scope the model query to certain permissions only.
     *
     * @param  Builder  $query
     * @param string|int|array|Permission|Collection  $permissions
     *
     * @return Builder
     */
    public function scopePermission(Builder $query, Permission|int|array|string|Collection $permissions): Builder
    {
        $permissions = $this->convertToPermissionModels($permissions);

        $rolesWithPermissions = array_unique(
            array_reduce(
                $permissions,
                function ($result, $permission) {
                    return array_merge($result, $permission->roles->all());
                },
                []
            )
        );

        return $query->where(
            function (Builder $query) use ($permissions, $rolesWithPermissions) {
                $query->whereHas(
                    'permissions',
                    function (Builder $subQuery) use ($permissions) {
                        $permissionClass = $this->getPermissionClass();
                        $key = (new $permissionClass())->getKeyName();
                        $subQuery->whereIn(
                            config('permission.table_names.permissions').".$key",
                            array_column($permissions, $key)
                        );
                    }
                );

                if (count($rolesWithPermissions) > 0) {
                    $query->orWhereHas(
                        'roles',
                        function (Builder $subQuery) use ($rolesWithPermissions) {
                            $roleClass = $this->getRoleClass();
                            $key = (new $roleClass())->getKeyName();
                            $subQuery->whereIn(
                                config('permission.table_names.roles').".$key",
                                array_column($rolesWithPermissions, $key)
                            );
                        }
                    );
                }
            }
        );
    }

    /**
     * @param  int|array|string|Collection|Permission  $permissions
     *
     * @return array
     * @throws PermissionDoesNotExist
     */
    protected function convertToPermissionModels(Permission|int|array|string|Collection $permissions): array
    {
        if ($permissions instanceof Collection) {
            $permissions = $permissions->all();
        }

        return array_map(
            function ($permission) {
                if ($permission instanceof Permission) {
                    return $permission;
                }
                $method = is_string($permission) ? 'findByCode' : 'findById';

                return $this->getPermissionClass()->{$method}($permission, $this->getDefaultGuardName());
            },
            Arr::wrap($permissions)
        );
    }

    /**
     * Determine if the model may perform the given permission.
     *
     * @param string|int|Permission  $permission
     * @param  string|null  $guardName
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo($permission, string $guardName = null): bool
    {
        // return $this->hasWildcardPermission($permission, $guardName);
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permissionModel = $permissionClass->findByCode(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (is_int($permission)) {
            $permissionModel = $permissionClass->findById(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (! $permissionModel instanceof Permission) {
            return false;
        }

        return $this->hasDirectPermission($permissionModel) || $this->hasPermissionViaRole($permissionModel);
    }

    /**
     * Validates a wildcard permission against all permissions of a user.
     *
     * @param  int|string|Permission  $permission
     * @param  string|null  $guardName
     *
     * @return bool
     */
    protected function hasWildcardPermission(Permission|int|string $permission, ?string $guardName = null): bool
    {
        $guardName = $guardName ?? $this->getDefaultGuardName();

        if (is_int($permission)) {
            $permission = $this->getPermissionClass()->findById($permission, $guardName);
        }

        if ($permission instanceof Permission) {
            $permission = $permission->name;
        }

        if (! is_string($permission)) {
            throw WildcardPermissionInvalidArgument::create();
        }

        foreach ($this->getAllPermissions() as $userPermission) {
            if ($guardName !== $userPermission->guard_name) {
                continue;
            }

            $userPermission = new WildcardPermission($userPermission->name);

            if ($userPermission->implies($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * An alias to hasPermissionTo(), but avoids throwing an exception.
     *
     * @param  int|string|Permission  $permission
     * @param  string|null  $guardName
     *
     * @return bool
     */
    public function checkPermissionTo(Permission|int|string $permission, string $guardName = null): bool
    {
        try {
            return $this->hasPermissionTo($permission, $guardName);
        } catch (PermissionDoesNotExist $e) {
            return false;
        }
    }

    /**
     * Determine if the model has any of the given permissions.
     *
     * @param string|int|array|Permission|Collection  ...$permissions
     *
     * @return bool
     */
    public function hasAnyPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->checkPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has all of the given permissions.
     *
     * @param string|int|array|Permission|Collection  ...$permissions
     *
     * @return bool
     * @throws Exception
     */
    public function hasAllPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->hasPermissionTo($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the model has, via roles, the given permission.
     *
     * @param  Permission  $permission
     *
     * @return bool
     */
    protected function hasPermissionViaRole(Permission $permission): bool
    {
        return $this->hasRole($permission->roles);
    }

    /**
     * Determine if the model has the given permission.
     *
     * @param  int|string|Permission  $permission
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasDirectPermission(Permission|int|string $permission): bool
    {
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByCode($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist();
        }

        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }

    /**
     * Return all the permissions the model has via roles.
     */
    public function getPermissionsViaRoles(): Collection
    {
        return $this->loadMissing('roles', 'roles.permissions')
            ->roles->flatMap(
                function ($role) {
                    return $role->permissions;
                }
            )->sort()->values();
    }

    /**
     * Return all the permissions the model has, both directly and via roles.
     */
    public function getAllPermissions(): Collection
    {
        $permissions = $this->permissions;

        if ($this->roles) {
            $permissions = $permissions->merge($this->getPermissionsViaRoles());
        }

        return $permissions->sort()->values();
    }

    /**
     * Grant the given permission(s) to a role.
     *
     * @param string|int|array|Permission|Collection  $permissions
     *
     * @return $this
     */
    public function givePermissionTo(...$permissions): static
    {
        $permissions = collect($permissions)
            ->flatten()
            ->reduce(
                function ($array, $permission) {
                    if (empty($permission)) {
                        return $array;
                    }

                    $permission = $this->getStoredPermission($permission);
                    if (! $permission instanceof Permission) {
                        return $array;
                    }

                    $this->ensureModelSharesGuard($permission);

                    $array[$permission->getKey()] = [];

                    return $array;
                },
                []
            );

        $model = $this->getModel();

        if ($model->exists) {
            $this->permissions()->sync($permissions, false);
            $model->load('permissions');
        } else {
            /** @var class-string<Model> $class */
            $class = get_class($model);

            $class::saved(
                function ($object) use ($permissions, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->permissions()->sync($permissions, false);
                    $model->load('permissions');
                }
            );
        }

        if (is_a($this, get_class(app(PermissionRegistrar::class)->getRoleClass()))) {
            $this->forgetCachedPermissions();
        }

        return $this;
    }

    /**
     * Remove all current permissions and set the given ones.
     *
     * @param string|int|array|Permission|Collection  $permissions
     *
     * @return $this
     */
    public function syncPermissions(...$permissions): static
    {
        $this->permissions()->detach();

        return $this->givePermissionTo($permissions);
    }

    /**
     * Revoke the given permission(s).
     *
     * @param  string|Permission|Permission[]|string[]  $permission
     *
     * @return $this
     */
    public function revokePermissionTo(array|Permission|string $permission): static
    {
        $this->permissions()->detach($this->getStoredPermission($permission));

        if (is_a($this, get_class(app(PermissionRegistrar::class)->getRoleClass()))) {
            $this->forgetCachedPermissions();
        }

        $this->load('permissions');

        return $this;
    }

    public function getPermissionNames(): Collection
    {
        return $this->permissions->pluck('name');
    }

    /**
     * @param  int|array|string|Collection|Permission  $permissions
     *
     * @return Permission|int|string|Collection|array
     */
    protected function getStoredPermission(Permission|int|array|string|Collection $permissions): Permission|int|string|Collection|array
    {
        $permissionClass = $this->getPermissionClass();

        if (is_numeric($permissions)) {
            return $permissionClass->findById($permissions, $this->getDefaultGuardName());
        }

        if (is_string($permissions)) {
            return $permissionClass->findByCode($permissions, $this->getDefaultGuardName());
        }

        if (is_array($permissions)) {
            $permissions = array_map(
                function ($permission) use ($permissionClass) {
                    return is_a($permission, get_class($permissionClass)) ? $permission->name : $permission;
                },
                $permissions
            );

            return $permissionClass
                ->whereIn('code', $permissions)
                ->whereIn('guard_name', $this->getGuardNames())
                ->get();
        }

        return $permissions;
    }

    /**
     * @param  Permission|Role  $roleOrPermission
     *
     * @throws GuardDoesNotMatch
     */
    protected function ensureModelSharesGuard(Permission|Role $roleOrPermission): void
    {
        if (! $this->getGuardNames()->contains($roleOrPermission->guard_name)) {
            throw GuardDoesNotMatch::create($roleOrPermission->guard_name, $this->getGuardNames());
        }
    }

    protected function getGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }

    /**
     * Forget the cached permissions.
     */
    public function forgetCachedPermissions(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Check if the model has All the requested Direct permissions.
     * @param string|int|array|Permission|Collection  ...$permissions
     * @return bool
     */
    public function hasAllDirectPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->hasDirectPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the model has Any of the requested Direct permissions.
     * @param string|int|array|Permission|Collection  ...$permissions
     * @return bool
     */
    public function hasAnyDirectPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->hasDirectPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
