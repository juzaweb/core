<?php

namespace Juzaweb\Core\Providers;

use App\Providers\ServiceProvider;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Compilers\BladeCompiler;
use Juzaweb\Core\Models\Permissions\Permission;
use Juzaweb\Core\Models\Permissions\Role;
use Juzaweb\Core\Permissions\Middleware\PermissionMiddleware;
use Juzaweb\Core\Permissions\Middleware\RoleMiddleware;
use Juzaweb\Core\Permissions\Middleware\RoleOrPermissionMiddleware;
use Juzaweb\Core\Permissions\PermissionRegistrar;
use Juzaweb\Core\Permissions\Contracts\Permission as PermissionContract;
use Juzaweb\Core\Permissions\Contracts\Role as RoleContract;

class PermissionServiceProvider extends ServiceProvider
{
    public function boot(PermissionRegistrar $permissionLoader): void
    {
        $this->registerMacroHelpers();

        $this->registerCommands();

        $this->registerModelBindings();

        // Before check user permission
        Gate::before(
            function ($user, $ability) {
                // Super admin has all permission
                /** @var \App\Models\User $user */
                if ($user->hasRoleAllPermissions()) {
                    return true;
                }

                if ($user->isBanned()) {
                    return false;
                }
            }
        );

        $permissionLoader->clearClassPermissions();
        $permissionLoader->registerPermissions();
        $this->registerMiddleware();

        $this->app->singleton(
            PermissionRegistrar::class,
            function ($app) use ($permissionLoader) {
                return $permissionLoader;
            }
        );
    }

    public function register(): void
    {
        $this->callAfterResolving(
            'blade.compiler',
            function (BladeCompiler $bladeCompiler) {
                $this->registerBladeExtensions($bladeCompiler);
            }
        );
    }

    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('role', RoleMiddleware::class);
        $router->aliasMiddleware('permission', PermissionMiddleware::class);
        $router->aliasMiddleware('role_or_permission', RoleOrPermissionMiddleware::class);
    }

    protected function registerCommands(): void
    {
        $this->commands(
            [
                \Juzaweb\Core\Permissions\Commands\CacheReset::class,
                \Juzaweb\Core\Permissions\Commands\CreateRole::class,
                \Juzaweb\Core\Permissions\Commands\CreatePermission::class,
                \Juzaweb\Core\Permissions\Commands\Show::class,
            ]
        );
    }

    protected function registerModelBindings(): void
    {
        $this->app->bind(PermissionContract::class, Permission::class);
        $this->app->bind(RoleContract::class, Role::class);
    }

    protected function registerBladeExtensions($bladeCompiler): void
    {
        $bladeCompiler->directive(
            'role',
            function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            }
        );

        $bladeCompiler->directive(
            'elserole',
            function ($arguments) {
                [$role, $guard] = explode(',', $arguments.',');

                return "<?php elseif(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            }
        );

        $bladeCompiler->directive(
            'endrole',
            function () {
                return '<?php endif; ?>';
            }
        );

        $bladeCompiler->directive(
            'hasrole',
            function ($arguments) {
                list($role, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasRole({$role})): ?>";
            }
        );
        $bladeCompiler->directive(
            'endhasrole',
            function () {
                return '<?php endif; ?>';
            }
        );

        $bladeCompiler->directive(
            'hasanyrole',
            function ($arguments) {
                [$roles, $guard] = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAnyRole({$roles})): ?>";
            }
        );

        $bladeCompiler->directive(
            'endhasanyrole',
            function () {
                return '<?php endif; ?>';
            }
        );

        $bladeCompiler->directive(
            'hasallroles',
            function ($arguments) {
                list($roles, $guard) = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasAllRoles({$roles})): ?>";
            }
        );

        $bladeCompiler->directive(
            'endhasallroles',
            function () {
                return '<?php endif; ?>';
            }
        );

        $bladeCompiler->directive(
            'unlessrole',
            function ($arguments) {
                [$role, $guard] = explode(',', $arguments.',');

                return "<?php if(!auth({$guard})->check() || ! auth({$guard})->user()->hasRole({$role})): ?>";
            }
        );
        $bladeCompiler->directive(
            'endunlessrole',
            function () {
                return '<?php endif; ?>';
            }
        );

        $bladeCompiler->directive(
            'hasexactroles',
            function ($arguments) {
                [$roles, $guard] = explode(',', $arguments.',');

                return "<?php if(auth({$guard})->check() && auth({$guard})->user()->hasExactRoles({$roles})): ?>";
            }
        );

        $bladeCompiler->directive(
            'endhasexactroles',
            function () {
                return '<?php endif; ?>';
            }
        );
    }

    protected function registerMacroHelpers(): void
    {
        Route::macro(
            'role',
            function ($roles = []) {
                $roles = implode('|', Arr::wrap($roles));

                $this->middleware("role:$roles");

                return $this;
            }
        );

        Route::macro(
            'permission',
            function ($permissions = []) {
                $permissions = implode('|', Arr::wrap($permissions));

                $this->middleware("permission:$permissions");

                return $this;
            }
        );
    }
}
