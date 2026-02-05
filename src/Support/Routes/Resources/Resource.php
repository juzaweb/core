<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
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

    /**
     * @param Registrar $registrar
     * @param string $name
     * @param string $controller
     */
    public function __construct(protected Registrar $registrar, protected string $name, protected string $controller) {}

    /**
     * Set route name
     *
     * @param string $name
     * @return static
     */
    public function name(string $name): static
    {
        $this->routeName = $name;

        return $this;
    }

    /**
     * Set permission name
     *
     * @param string $name
     * @return static
     */
    public function permissionName(string $name): static
    {
        $this->permissionName = $name;

        return $this;
    }

    /**
     * Set permissions for a method
     *
     * @param string $method
     * @param array $permissions
     * @return static
     */
    public function permissions(string $method, array $permissions): static
    {
        $this->permissions[$method] = $permissions;

        return $this;
    }

    /**
     * Set middleware for a method
     *
     * @param string $method
     * @param array $middleware
     * @return static
     */
    public function middleware(string $method, array $middleware): static
    {
        $this->middleware[$method] = $middleware;

        return $this;
    }

    /**
     * Set methods to register
     *
     * @param array $methods
     * @return static
     */
    public function methods(array $methods): static
    {
        $this->methods = $methods;

        return $this;
    }

    /**
     * Only register specific methods
     *
     * @param array $methods
     * @return static
     */
    public function only(array $methods): static
    {
        $this->methods($methods);

        return $this;
    }

    /**
     * Register all methods except specific ones
     *
     * @param array $methods
     * @return static
     */
    public function except(array $methods): static
    {
        $this->methods(array_diff($this->methods, $methods));

        return $this;
    }

    /**
     * Disable permission check
     *
     * @return static
     */
    public function noPermission(): static
    {
        return $this->withoutPermission();
    }

    /**
     * Set without permission
     *
     * @param bool $withoutPermission
     * @return static
     */
    public function withoutPermission(bool $withoutPermission = true): static
    {
        $this->withoutPermission = $withoutPermission;

        return $this;
    }

    /**
     * Get permission name
     *
     * @return string
     */
    public function getPermissionName(): string
    {
        return $this->permissionName ?? $this->name;
    }

    /**
     * Get permissions for a method
     *
     * @param string $method
     * @return array
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

        return [$this->getPermissionName() . ".{$permission}"];
    }

    /**
     * Get route name
     *
     * @return string
     */
    public function getRouteName(): string
    {
        return $this->routeName ?? "admin.{$this->name}";
    }

    /**
     * Register routes
     *
     * @return void
     */
    abstract public function register(): void;

    public function __destruct()
    {
        if (! $this->registered) {
            $this->register();
        }
    }
}
