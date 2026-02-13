<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Juzaweb\Modules\Admin\Enums\UserStatus;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\DataTables\UsersDataTable;
use Juzaweb\Modules\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Modules\Core\Http\Requests\UserRequest;
use Juzaweb\Modules\Admin\Models\User;
use Juzaweb\Modules\Core\Permissions\Models\Role;

class UserController extends AdminController
{
    public function index(UsersDataTable $dataTable)
    {
        Breadcrumb::add(__('core::translation.users'));

        return $dataTable->render(
            'core::admin.user.index',
            []
        );
    }

    public function create()
    {
        Breadcrumb::add(__('core::translation.users'), admin_url('users'));

        Breadcrumb::add(__('core::translation.add_user'));

        $model = new User();
        $action = action([static::class, 'store']);
        $roles = Role::get();

        return view(
            'core::admin.user.form',
            compact('model', 'action', 'roles')
        );
    }

    public function edit(string $id)
    {
        $model = User::where('is_super_admin', '!=', true)->find($id);

        abort_if($model === null, 404, __('core::translation.user_not_found'));

        Breadcrumb::add(__('core::translation.users'), admin_url('users'));

        Breadcrumb::add(__('core::translation.edit_user_name', ['name' => $model->name]));

        $action = action([static::class, 'update'], ['id' => $model->id]);
        $roles = Role::get();

        return view(
            'core::admin.user.form',
            compact('model', 'action', 'roles')
        );
    }

    public function store(UserRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->all();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $model = User::create($data);

        $model->syncRoles($request->input('roles', []));

        return $this->success(
            [
                'message' => __('core::translation.user_name_created_successfully', ['name' => $model->name]),
                'redirect' => admin_url('users'),
            ]
        );
    }

    public function update(UserRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $model = User::where('is_super_admin', '!=', true)->find($id);

        abort_if($model === null, 404, __('core::translation.user_not_found'));

        $data = $request->safe()->all();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $model->update($data);

        $model->syncRoles($request->input('roles', []));

        return $this->success(
            __('core::translation.user_name_updated_successfully', ['name' => $model->name]),
        );
    }

    public function destroy(string $id): JsonResponse|RedirectResponse
    {
        $model = User::where('is_super_admin', '!=', true)->find($id);

        abort_if($model === null, 404, __('core::translation.user_not_found'));

        $model->delete();

        return $this->success(
            __('core::translation.user_name_deleted_successfully', ['name' => $model->name]),
        );
    }

    public function bulk(BulkActionsRequest $request): JsonResponse|RedirectResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        switch ($action) {
            case 'delete':
                User::whereIn('id', $ids)->where('is_super_admin', '!=', true)->delete();
                return $this->success(__('core::translation.selected_users_deleted_successfully'));
            case 'activate':
                User::whereIn('id', $ids)->where('is_super_admin', '!=',
                    true)->update(['status' => UserStatus::ACTIVE]);
                return $this->success(__('core::translation.selected_users_activated_successfully'));
            case 'deactivate':
                User::whereIn('id', $ids)->where('is_super_admin', '!=',
                    true)->update(['status' => UserStatus::INACTIVE]);
                return $this->success(__('core::translation.selected_users_deactivated_successfully'));
            case 'banned':
                User::whereIn('id', $ids)->where('is_super_admin', '!=',
                    true)->update(['status' => UserStatus::BANNED]);
                return $this->success(__('core::translation.selected_users_banned_successfully'));
            default:
                return $this->error(__('core::translation.invalid_action'));
        }
    }
}
