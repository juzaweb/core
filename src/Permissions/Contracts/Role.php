<?php

namespace Juzaweb\Modules\Core\Permissions\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Juzaweb\Modules\Core\Permissions\Exceptions\RoleDoesNotExist;

/**
 * @mixin Model
 */
interface Role
{
    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany;

    /**
     * Find a role by its name and guard name.
     *
     * @param  string|null  $guardName
     * @return \Spatie\Permission\Contracts\Role
     *
     * @throws RoleDoesNotExist
     */
    public static function findByName(string $name, $guardName): self;

    /**
     * Find a role by its id and guard name.
     *
     * @param  string|null  $guardName
     * @return \Spatie\Permission\Contracts\Role
     *
     * @throws RoleDoesNotExist
     */
    public static function findById(int $id, $guardName): self;

    /**
     * Find or create a role by its name and guard name.
     *
     * @param  string|null  $guardName
     * @return \App\Contracts\Permissions\Role
     */
    public static function findOrCreate(string $name, $guardName): self;

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  string|Permission  $permission
     */
    public function hasPermissionTo($permission): bool;
}
