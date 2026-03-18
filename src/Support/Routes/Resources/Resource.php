<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Routes\Resources;

use Illuminate\Contracts\Routing\Registrar;

abstract class Resource
{
    protected bool $registered = false;

    protected string $permissionName;

    /**
     * @var string[]
     */
    protected array $methods = [];

    /**
     * @var array<string, string[]>
     */
    protected array $permissions = [];

    /**
     * @var array<string, string[]>
     */
    protected array $middleware = [];

    protected bool $withoutPermission = false;

    protected ?string $routeName = null;

    public function __construct(protected Registrar $registrar, protected string $name, protected string $controller) {}

    /**
     * Set route name
     */
    public function name(string $name): static
    {
        $this->routeName = $name;

        return $this;
    }

    /**
     * Set permission name
     */
    public function permissionName(string $name): static
    {
        $this->permissionName = $name;

        return $this;
    }

    /**
     * Set permissions for a method
     */
    public function permissions(string $method, array $permissions): static
    {
        $this->permissions[$method] = $permissions;

        return $this;
    }

    /**
     * Set middleware for a method
     */
    public function middleware(string $method, array $middleware): static
    {
        $this->middleware[$method] = $middleware;

        return $this;
    }

    /**
     * Set methods to register
     */
    public function methods(array $methods): static
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * Only register specific methods
     */
    public function only(array $methods): static
    {
        $this->methods($methods);

        return $this;
    }

    /**
     * Register all methods except specific ones
     */
    public function except(array $methods): static
    {
        $this->methods(array_diff($this->methods, $methods));

        return $this;
    }

    /**
     * Disable permission check
     */
    public function noPermission(): static
    {
        return $this->withoutPermission();
    }

    /**
     * Set without permission
     */
    public function withoutPermission(bool $withoutPermission = true): static
    {
        $this->withoutPermission = $withoutPermission;

        return $this;
    }

    /**
     * Get permission name
     */
    public function getPermissionName(): string
    {
        return $this->permissionName ?? $this->name;
    }

    /**
     * Get permissions for a method
     */
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

    /**
     * Get route name
     */
    public function getRouteName(): string
    {
        return $this->routeName ?? "admin.{$this->name}";
    }

    /**
     * Register routes
     */
    abstract public function register(): void;

    public function __destruct()
    {
        if (! $this->registered) {
            $this->register();
        }
    }
}
