<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\RolesDataTable;
use Juzaweb\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Core\Http\Requests\RoleRequest;
use Juzaweb\Permissions\Models\Group;
use Juzaweb\Permissions\Models\Permission;
use Juzaweb\Permissions\Models\Role;

class RoleController extends AdminController
{
    public function index(RolesDataTable $dataTable)
    {
        Breadcrumb::add(__('Roles'));

        return $dataTable->render('core::admin.role.index');
    }

    public function create()
    {
        Breadcrumb::add(__('Roles'), admin_url('roles'));

        Breadcrumb::add(__('Add Role'));

        $model = new Role();
        $action = action([static::class, 'store']);
        $groups = Group::get();
        $permissions = Permission::get()->groupBy('group');

        return view(
            'core::admin.role.form',
            compact('model', 'action', 'permissions', 'groups')
        );
    }

    public function edit(string $id)
    {
        $model = Role::find($id);

        abort_if($model === null, 404, __('Role not found'));

        Breadcrumb::add(__('Roles'), admin_url('roles'));

        Breadcrumb::add(__('Edit Role: :name', ['name' => $model->name]));

        $action = action([static::class, 'update'], ['id' => $model->id]);
        $groups = Group::get();
        $permissions = Permission::get()->groupBy('group');

        return view(
            'core::admin.role.form',
            compact('model', 'action', 'permissions', 'groups')
        );
    }

    public function store(RoleRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->all();

        $model = Role::create($data);

        return $this->success(
            __('Role :name created successfully', ['name' => $model->name]),
        );
    }

    public function update(RoleRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $model = Role::find($id);

        abort_if($model === null, 404, __('Role not found'));

        $data = $request->safe()->all();

        $model->update($data);

        return $this->success(
            __('Role :name updated successfully', ['name' => $model->name]),
        );
    }

    public function destroy(string $id): JsonResponse|RedirectResponse
    {
        $model = Role::find($id);

        abort_if($model === null, 404, __('Role not found'));

        $model->delete();

        return $this->success(
            __('Role :name deleted successfully', ['name' => $model->name]),
        );
    }

    public function bulk(BulkActionsRequest $request): JsonResponse|RedirectResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        switch ($action) {
            case 'delete':
                Role::whereIn('id', $ids)->delete();
                return $this->success(__('Selected roles deleted successfully'));
            default:
                return $this->error(__('Invalid action'));
        }
    }
}
