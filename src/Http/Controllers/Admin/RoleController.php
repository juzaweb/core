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
use Juzaweb\Modules\Core\Permissions\Models\Permission;
use Juzaweb\Modules\Core\Permissions\Models\Role;

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
        $permissions = Permission::get()->groupBy('group');

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

                $role->syncPermissions($request->input('permissions', []));

                return $role;
            }
        );

        return $this->success(
            __('core::translation.created_successfully'),
            ['redirect' => admin_url('roles')]
        );
    }

    public function edit(Role $role): View
    {
        Breadcrumb::add(__('core::translation.roles'), admin_url('roles'));
        Breadcrumb::add(__('core::translation.edit_role'));

        $permissions = Permission::get()->groupBy('group');
        $model = $role;

        return view(
            'core::admin.role.form',
            compact('model', 'permissions')
        );
    }

    public function update(RoleRequest $request, Role $role): JsonResponse|RedirectResponse
    {
        DB::transaction(
            function () use ($request, $role) {
                $role->update($request->safe()->all());

                $role->syncPermissions($request->input('permissions', []));
            }
        );

        return $this->success(
            __('core::translation.updated_successfully')
        );
    }

    public function destroy(Role $role): JsonResponse|RedirectResponse
    {
        $role->delete();

        return $this->success(
            __('core::translation.deleted_successfully')
        );
    }
}
