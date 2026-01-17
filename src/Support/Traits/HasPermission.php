<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Traits;

trait HasPermission
{
    protected array $permissions = [];

    protected array $defaultPermissions = ['super-admin'];

    protected array $roles = [];

    protected bool $noPermission = false;

    public function permissions(array|string $permissions): static
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        $this->permissions = $permissions;

        return $this;
    }

    public function defaultPermissions(array $defaultPermissions): static
    {
        $this->defaultPermissions = $defaultPermissions;

        return $this;
    }

    public function withoutPermissions(): static
    {
        $this->permissions = [];

        $this->roles = [];

        $this->defaultPermissions = [];

        $this->noPermission = true;

        return $this;
    }

    public function noPermission(): static
    {
        return $this->withoutPermissions();
    }

    public function getPermissions(): array
    {
        if ($this->noPermission) {
            return [];
        }

        return $this->permissions ?: $this->getDefaultPermissions();
    }

    public function getDefaultPermissions(): array
    {
        return $this->defaultPermissions;
    }

    public function roles(array|string $roles): static
    {
        if (is_string($roles)) {
            $roles = [$roles];
        }

        $this->roles = $roles;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }
}
