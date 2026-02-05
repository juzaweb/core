<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Routes\Resources;

use Juzaweb\Modules\Core\Facades\PermissionManager;

class AdminResource extends Resource
{
    protected array $methods = ['index', 'edit', 'create', 'store', 'update', 'destroy', 'bulk'];

    /**
     * Register routes
     *
     * @return void
     */
    public function register(): void
    {
        $this->registered = true;
        $routeName = $this->getRouteName();

        if (in_array('index', $this->methods)) {
            $this->registrar->get($this->name, [$this->controller, 'index'])
                ->middleware($this->getMiddleware('index'))
                ->name("{$routeName}.index");

            PermissionManager::make(
                "{$this->name}.index",
                fn() => [
                    'name' => "Index {$this->name}",
                    'group' => $this->name,
                    'code' => "{$this->name}.index",
                ]
            );
        }

        if (in_array('edit', $this->methods)) {
            $this->registrar->get("{$this->name}/{id}/edit", [$this->controller, 'edit'])
                ->middleware($this->getMiddleware('edit'))
                ->name("{$routeName}.edit");

            PermissionManager::make(
                "{$this->name}.edit",
                fn() => [
                    'name' => "Edit {$this->name}",
                    'group' => $this->name,
                    'code' => "{$this->name}.edit",
                ]
            );
        }

        if (in_array('create', $this->methods)) {
            $this->registrar->get("{$this->name}/create", [$this->controller, 'create'])
                ->middleware($this->getMiddleware('create'))
                ->name("{$routeName}.create");

            PermissionManager::make(
                "{$this->name}.create",
                fn() => [
                    'name' => "Create {$this->name}",
                    'group' => $this->name,
                    'code' => "{$this->name}.create",
                ]
            );
        }

        if (in_array('store', $this->methods)) {
            $this->registrar->post($this->name, [$this->controller, 'store'])
                ->middleware($this->getMiddleware('create'))
                ->name("{$routeName}.store");
        }

        if (in_array('update', $this->methods)) {
            $this->registrar->put("{$this->name}/{id}", [$this->controller, 'update'])
                ->middleware($this->getMiddleware('edit'))
                ->name("{$routeName}.update");
        }

        if (in_array('destroy', $this->methods)) {
            $this->registrar->delete("{$this->name}/{id}", [$this->controller, 'destroy'])
                ->middleware($this->getMiddleware('destroy'))
                ->name("{$routeName}.destroy");

            PermissionManager::make(
                "{$this->name}.delete",
                fn() => [
                    'name' => "Delete {$this->name}",
                    'group' => $this->name,
                    'code' => "{$this->name}.delete",
                ]
            );
        }

        if (in_array('bulk', $this->methods)) {
            $this->registrar->post("{$this->name}/bulk", [$this->controller, 'bulk'])
                ->middleware($this->getMiddleware('bulk'))
                ->name("{$routeName}.bulk");
        }
    }

    /**
     * Get middleware for a method
     *
     * @param string $method
     * @return array
     */
    public function getMiddleware(string $method): array
    {
        if (isset($this->middleware[$method])) {
            return $this->middleware[$method];
        }

        $middleware = [];

        if ($permissions = $this->getPermissions($method)) {
            $middleware[] = 'permission:' . implode(',', $permissions);
        }

        return $middleware;
    }
}
