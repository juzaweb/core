<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support;

use Juzaweb\Modules\Core\Contracts\PermissionManager as PermissionManagerContract;

class PermissionManager implements PermissionManagerContract
{
    protected array $permissions = [];

    public function make(string $key, callable $callback): void
    {
        $this->permissions[$key] = $callback;
    }

    public function getPermissions(): array
    {
        $results = [];

        foreach ($this->permissions as $key => $callback) {
            $results[$key] = call_user_func($callback);
        }

        return $results;
    }

    public function collection(): \Illuminate\Support\Collection
    {
        return collect($this->getPermissions());
    }
}
