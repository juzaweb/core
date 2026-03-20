<?php

namespace Juzaweb\Modules\Core\Permissions\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Juzaweb\Modules\Core\Permissions\Exceptions\PermissionDoesNotExist;

interface Permission
{
    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany;

    /**
     * Find a permission by its name.
     *
     * @param  string|null  $guardName
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByName(string $name, $guardName): ?self;

    /**
     * Find a permission by its code.
     *
     *
     * @throws PermissionDoesNotExist
     */
    public static function findByCode(string $code, ?string $guardName): ?self;

    /**
     * Find a permission by its id.
     *
     * @param  string|null  $guardName
     *
     * @throws PermissionDoesNotExist
     */
    public static function findById(int $id, $guardName): ?self;

    /**
     * Find or Create a permission by its name and guard name.
     *
     * @param  string|null  $guardName
     */
    public static function findOrCreate(string $name, $guardName): self;
}
