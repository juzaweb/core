<?php

namespace Juzaweb\Core\Permissions\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Juzaweb\Core\Facades\GlobalData;
use Juzaweb\Core\Models\ApiScope;
use Juzaweb\Core\Models\ApiScopeGroup;
use Juzaweb\Core\Models\Permissions\Group;
use Juzaweb\Core\Models\Permissions\Permission;
use Juzaweb\Core\Models\Permissions\Role;
use Juzaweb\Core\Permissions\Guard;
use App\Models\User;
use Illuminate\Support\Str;
use Juzaweb\Core\Permissions\PermissionRegistrar;
use Symfony\Component\Console\Input\InputOption;

class Generate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'permission:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Roles and permissions generator';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        [$permissions, $scopes] = $this->collectInRoutes();

        $this->generatePermissions($permissions);

        $this->generateScopes($scopes);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return Command::SUCCESS;
    }

    protected function generatePermissions(Collection $permissions): void
    {
        $permissions = $permissions
            ->merge(
                GlobalData::collect('permissions')
                    ->flatten(1)
                    ->keyBy('key')
                    ->map(
                        function ($permission) {
                            $permission['code'] = $permission['key'];

                            return $permission;
                        }
                    )
            );

        $group = GlobalData::collect('permission_groups')->map(
            fn ($group) => [
                'code' => $group['key'],
                'name' => $group['name'],
                'description' => $group['description'],
                'priority' => $group['priority'],
            ]
        );

        $groups = $permissions->pluck('group')
            ->unique()
            ->map(
                function ($group) {
                    return [
                        'code' => $group,
                        'name' => $this->getNamePermission($group),
                        'priority' => 1,
                    ];
                }
            )
            ->keyBy('code')
            ->merge($group);

        $roles = GlobalData::collect('roles');

        // if ($this->option('force')) {
        //     Group::query()->delete();
        //     Permission::query()->delete();
        //     Role::query()->delete();
        // } else {
        //
        //
        //     $existRoles = Role::whereIn('code', $roles->keys())->get(['code']);
        //     $roles = $roles->whereNotIn('code', $existRoles->pluck('code'));
        // }

        $existGroups = Group::whereIn('code', $groups->keys())->get(['code']);
        $groups = $groups->whereNotIn('code', $existGroups->pluck('code'));

        $existPermissions = Permission::whereIn('code', $permissions->keys())->get(['code']);
        $permissions = $permissions->whereNotIn('code', $existPermissions->pluck('code'));

        Group::insert(
            $groups->map(function ($group) {
                return [
                    'code' => $group['code'],
                    'name' => $group['name'],
                    'description' => $group['description'] ?? null,
                    'priority' => $group['priority'] ?? 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->values()
            ->toArray()
        );

        Permission::insert(
            $permissions->map(function ($permission) {
                return [
                    'code' => $permission['code'],
                    'name' => $permission['name'],
                    'guard_name' => $permission['guard_name'] ?? Guard::getDefaultName(User::class),
                    'description' => $permission['description'] ?? null,
                    'group' => $permission['group'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->values()
            ->toArray()
        );

        foreach ($roles as $role => $options) {
            $model = Role::updateOrCreate(['code' => $role], $options);

            if (! empty($options['permissions'])) {
                $model->givePermissionTo($options['default_permissions']);
            }

            if (! empty($options['grant_all_permissions'])) {
                $model->givePermissionTo(Permission::get());
            }
        }
    }

    protected function generateScopes(Collection $scopes): void
    {
        $groups = $scopes->pluck('group')
            ->unique()
            ->map(
                function ($group) {
                    return [
                        'code' => trim($group),
                        'name' => $this->getNamePermission($group),
                    ];
                }
            );

        // if ($this->option('force')) {
        //     ApiScope::query()->delete();
        //     ApiScopeGroup::query()->delete();
        // }

        $existScopes = ApiScope::whereIn('code', $scopes->keys())->get(['code']);
        $scopes = $scopes->whereNotIn('code', $existScopes->pluck('code'));

        $existGroups = ApiScopeGroup::whereIn('code', $groups->pluck('code'))->get(['code']);
        $groups = $groups->whereNotIn('code', $existGroups->pluck('code'));

        $groupData = $groups->map(
            function ($group) {
                return [
                    'code' => $group['code'],
                    'name' => $group['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        )
        ->unique('code')
        ->values()
        ->toArray();

        ApiScopeGroup::insert($groupData);

        ApiScope::insert(
            $scopes->map(function ($scope) {
                return [
                    'code' => $scope['code'],
                    'name' => $scope['name'],
                    'group_code' => $scope['group'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })
            ->values()
            ->toArray()
        );
    }

    protected function collectInRoutes(): array
    {
        /** @var \Illuminate\Routing\RouteCollection $routers */
        $routers = app('router')->getRoutes();

        $permissions = [];
        $scopes = [];

        foreach ($routers->getRoutes() as $route) {
            foreach ($route->middleware() as $middleware) {
                if (str_starts_with($middleware, 'permission:')) {
                    $permission = explode('|', str_replace('permission:', '', $middleware));

                    foreach ($permission as $item) {
                        if (!isset($permissions[$item])) {
                            $permissions[$item] = [
                                'code' => trim($item),
                                'name' => $this->getNamePermission($item),
                                'guard_name' => Guard::getDefaultName(User::class),
                                'group' => explode('.', trim($item))[0],
                            ];
                        }
                    }
                }

                if (str_starts_with($middleware, 'scope:')) {
                    $scope = explode(',', str_replace('scope:', '', $middleware));

                    foreach ($scope as $item) {
                        if (!isset($scopes[$item])) {
                            $scopes[$item] = [
                                'code' => trim($item),
                                'name' => $this->getNamePermission($item),
                                'group' => explode('.', trim($item))[0],
                            ];
                        }
                    }
                }

                if (str_starts_with($middleware, 'scopes:')) {
                    $scope = explode(',', str_replace('scopes:', '', $middleware));

                    foreach ($scope as $item) {
                        if (!isset($scopes[$item])) {
                            $scopes[$item] = [
                                'code' => trim($item),
                                'name' => $this->getNamePermission($item),
                                'group' => explode('.', trim($item))[0],
                            ];
                        }
                    }
                }
            }
        }

        unset($permissions['super-admin']);

        return [collect($permissions), collect($scopes)];
    }

    protected function getNamePermission(string $permission): string
    {
        return title_from_key(trim($permission));
    }

    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force override permissions'],
        ];
    }
}
