<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Routes\Resources;

class APIResource extends Resource
{
    /**
     * @var array $readScopes Read scopes
     */
    protected array $readScopes;

    /**
     * @var array $writeScopes Write scopes
     */
    protected array $writeScopes;

    /**
     * @var array|string[] $methods API methods
     */
    protected array $methods = ['index', 'show', 'store', 'update', 'destroy', 'bulk'];

    protected array $readMethods = ['index', 'show'];

    protected string $scopeName;

    protected array $scopes = [];

    protected bool $guestable = false;

    protected array $guestMethods = ['index', 'show'];

    public function readScopes(array $scopes): static
    {
        $this->readScopes = $scopes;

        return $this;
    }

    public function writeScopes(array $scopes): static
    {
        $this->writeScopes = $scopes;

        return $this;
    }

    /**
     * Set the scope name.
     *
     * @param string $name
     * @return static
     */
    public function scopeName(string $name): static
    {
        $this->scopeName = $name;

        return $this;
    }

    /**
     * Set the scopes for API methods.
     *
     * @param array $scopes
     * @return static
     */
    public function scopes(array $scopes): static
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * Except the bulk action in the API resource.
     *
     * @return static
     */
    public function exceptBulkAction(): static
    {
        $this->methods(array_diff($this->methods, ['bulk']));

        return $this;
    }

    public function guestable(bool $guestable = true): static
    {
        $this->guestable = $guestable;

        return $this;
    }

    public function guestMethods(array $methods): static
    {
        $this->guestMethods = $methods;

        return $this;
    }

    /**
     * Register the API resource.
     *
     * This method registers the API resource routes (index, show, store, update, destroy, bulk)
     * with the given controller and middleware.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registered = true;

        if (in_array('index', $this->methods)) {
            $this->registrar->get($this->name, [$this->controller, 'index'])
                ->middleware($this->getMiddleware('index'));
        }

        if (in_array('show', $this->methods)) {
            $this->registrar->get("{$this->name}/{id}", [$this->controller, 'show'])
                ->middleware($this->getMiddleware('show'));
        }

        if (in_array('store', $this->methods)) {
            $this->registrar->post($this->name, [$this->controller, 'store'])
                ->middleware($this->getMiddleware('store'));
        }

        if (in_array('update', $this->methods)) {
            $this->registrar->put("{$this->name}/{id}", [$this->controller, 'update'])
                ->middleware($this->getMiddleware('update'));
        }

        if (in_array('destroy', $this->methods)) {
            $this->registrar->delete("{$this->name}/{id}", [$this->controller, 'destroy'])
                ->middleware($this->getMiddleware('destroy'));
        }

        if (in_array('bulk', $this->methods)) {
            $this->registrar->post("{$this->name}/bulk", [$this->controller, 'bulk'])
                ->middleware($this->getMiddleware('bulk'));
        }
    }

    public function getMiddleware(string $method): array
    {
        if (isset($this->middleware[$method])) {
            return $this->middleware[$method];
        }

        if ($this->guestable && in_array($method, $this->guestMethods)) {
            return [];
        }

        $middleware = [];

        if ($scopes = $this->getScopes($method)) {
            $middleware[] = 'scope:'. implode(',', $scopes);
        }

        if ($permissions = $this->getPermissions($method)) {
            $middleware[] = 'permission:'. implode(',', $permissions);
        }

        return $middleware;
    }

    public function getScopeName(): string
    {
        return $this->scopeName ?? $this->name;
    }

    public function getScopes(string $method): array
    {
        if (isset($this->scopes[$method])) {
            return $this->scopes[$method];
        }

        if (in_array($method, $this->readMethods)) {
            return $this->getReadScopes();
        }

        return $this->getWriteScopes();
    }

    public function getWriteScopes(): array
    {
        if (isset($this->writeScopes)) {
            return $this->writeScopes;
        }

        $this->writeScopes = ["{$this->getScopeName()}.write", "{$this->getScopeName()}.all"];

        return $this->writeScopes;
    }

    public function getReadScopes(): array
    {
        if (isset($this->readScopes)) {
            return $this->readScopes;
        }

        $this->readScopes = ["{$this->getScopeName()}.read", "{$this->getScopeName()}.all"];

        return $this->readScopes;
    }
}
