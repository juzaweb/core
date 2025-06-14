<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Support\Routes\Resources;

use Illuminate\Contracts\Routing\Registrar;

abstract class Resource
{
    protected bool $registered = false;

    protected string $permissionName;

    protected array $methods = [];

    protected array $permissions = [];

    protected array $middleware = [];

    protected bool $withoutPermission = false;

    public function __construct(protected Registrar $registrar, protected string $name, protected string $controller)
    {
    }

    public function permissionName(string $name): static
    {
        $this->permissionName = $name;

        return $this;
    }

    public function permissions(string $method, array $permissions): static
    {
        $this->permissions[$method] = $permissions;

        return $this;
    }

    public function middleware(string $method, array $middleware): static
    {
        $this->middleware[$method] = $middleware;

        return $this;
    }

    public function methods(array $methods): static
    {
        $this->methods = $methods;

        return $this;
    }

    public function only(array $methods): static
    {
        $this->methods($methods);

        return $this;
    }

    public function except(array $methods): static
    {
        $this->methods(array_diff($this->methods, $methods));

        return $this;
    }

    public function noPermission(): static
    {
        return $this->withoutPermission();
    }

    public function withoutPermission(bool $withoutPermission = true): static
    {
        $this->withoutPermission = $withoutPermission;

        return $this;
    }

    public function getPermissionName(): string
    {
        return $this->permissionName ?? $this->name;
    }

    public function getPermissions(string $method): array
    {
        if ($this->withoutPermission) {
            return [];
        }

        if (isset($this->permissions[$method])) {
            return $this->permissions[$method];
        }

        $permission = match ($method) {
            'store' => 'create',
            'update', 'bulk' => 'edit',
            'destroy' => 'delete',
            default => $method,
        };

        return [$this->getPermissionName().".{$permission}"];
    }

    abstract public function register(): void;

    public function __destruct()
    {
        if (! $this->registered) {
            $this->register();
        }
    }
}
