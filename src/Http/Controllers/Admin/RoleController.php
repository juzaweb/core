<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\DataTables\RolesDataTable;
use Juzaweb\Modules\Core\Http\Requests\RoleRequest;
use Juzaweb\Modules\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Modules\Core\Permissions\Models\Permission;
use Juzaweb\Modules\Core\Permissions\Models\Role;
use Juzaweb\Modules\Core\Facades\PermissionManager;
use Juzaweb\Modules\Core\Permissions\PermissionRegistrar;

class RoleController extends AdminController
{
    public function index(RolesDataTable $dataTable): View|JsonResponse
    {
        Breadcrumb::add(__('core::translation.roles'));

        return $dataTable->render('core::admin.role.index');
    }

    public function create(): View
    {
        Breadcrumb::add(__('core::translation.roles'), admin_url('roles'));
        Breadcrumb::add(__('core::translation.add_role'));

        $model = new Role();
        $permissions = collect(PermissionManager::getPermissions())->groupBy('group');

        return view(
            'core::admin.role.form',
            compact('permissions', 'model')
        );
    }

    public function store(RoleRequest $request): JsonResponse|RedirectResponse
    {
        $role = DB::transaction(
            function () use ($request) {
                $role = Role::create($request->safe()->all());

                $this->createPermissionsIfNotExists($request->input('permissions', []));
                $role->syncPermissions($request->input('permissions', []));

                return $role;
            }
        );

        return $this->success(
            __('core::translation.created_successfully'),
            ['redirect' => admin_url('roles')]
        );
    }

    public function edit($id): View
    {
        $role = Role::findOrFail($id);

        Breadcrumb::add(__('core::translation.roles'), admin_url('roles'));
        Breadcrumb::add(__('core::translation.edit_role'));

        $permissions = collect(PermissionManager::getPermissions())->groupBy('group');
        $model = $role;

        return view(
            'core::admin.role.form',
            compact('model', 'permissions')
        );
    }

    public function update(RoleRequest $request, $id): JsonResponse|RedirectResponse
    {
        $role = Role::findOrFail($id);

        DB::transaction(
            function () use ($request, $role) {
                $role->update($request->safe()->all());

                $this->createPermissionsIfNotExists($request->input('permissions', []));
                $role->syncPermissions($request->input('permissions', []));
            }
        );

        return $this->success(
            __('core::translation.updated_successfully')
        );
    }

    public function destroy($id): JsonResponse|RedirectResponse
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return $this->success(
            __('core::translation.deleted_successfully')
        );
    }

    public function bulk(BulkActionsRequest $request): JsonResponse|RedirectResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        switch ($action) {
            case 'delete':
                Role::whereIn('id', $ids)->delete();
                app(PermissionRegistrar::class)->forgetCachedPermissions();
                return $this->success(__('core::translation.deleted_successfully'));
        }

        return $this->error(__('core::translation.invalid_action'));
    }

    /**
     * Create permissions from PermissionManager if they don't exist in database
     *
     * @param array $permissionCodes
     * @return void
     */
    protected function createPermissionsIfNotExists(array $permissionCodes): void
    {
        $allPermissions = PermissionManager::collection();

        // Get existing permission codes from database
        $existingCodes = Permission::whereIn('code', $permissionCodes)
            ->pluck('code')
            ->toArray();

        // Filter out codes that already exist
        $newCodes = array_diff($permissionCodes, $existingCodes);

        foreach ($newCodes as $code) {
            $permissionData = $allPermissions->get($code);

            if (!$permissionData) {
                throw new \RuntimeException("Permission with code '{$code}' not found in PermissionManager");
            }

            Permission::create([
                'code' => $code,
                'name' => $permissionData['name'] ?? $code,
                'group' => $permissionData['group'] ?? null,
                'description' => $permissionData['description'] ?? null,
            ]);
        }
    }
}
