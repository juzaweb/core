<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Support\Collection;

interface PermissionManager
{
    /**
     * Register a permission.
     */
    public function make(string $key, callable $callback): void;

    /**
     * Get all registered permissions.
     */
    public function getPermissions(): array;

    /**
     * Get all registered permissions as a Collection.
     */
    public function collection(): Collection;
}
