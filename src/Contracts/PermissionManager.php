<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

interface PermissionManager
{
    /**
     * Register a permission.
     *
     * @param string $key
     * @param callable $callback
     * @return void
     */
    public function make(string $key, callable $callback): void;

    /**
     * Get all registered permissions.
     *
     * @return array
     */
    public function getPermissions(): array;

    /**
     * Get all registered permissions as a Collection.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection(): \Illuminate\Support\Collection;
}
