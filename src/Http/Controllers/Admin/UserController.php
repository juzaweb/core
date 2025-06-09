<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\UsersDataTable;
use Juzaweb\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Core\Http\Requests\UserRequest;
use Juzaweb\Core\Models\Enums\UserStatus;
use Juzaweb\Permissions\Models\Role;

class UserController extends AdminController
{
    public function index(UsersDataTable $dataTable)
    {
        Breadcrumb::add(__('Users'));

        return $dataTable->render(
            'core::admin.user.index',
            []
        );
    }

    public function create()
    {
        Breadcrumb::add(__('Users'), admin_url('users'));

        Breadcrumb::add(__('Add User'));

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
        $model = User::find($id);

        abort_if($model === null, 404, __('User not found'));

        Breadcrumb::add(__('Users'), admin_url('users'));

        Breadcrumb::add(__('Edit User: :name', ['name' => $model->name]));

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

        return $this->success(
            __('User :name created successfully', ['name' => $model->name]),
        );
    }

    public function update(UserRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $model = User::find($id);

        abort_if($model === null, 404, __('User not found'));

        $data = $request->safe()->all();

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $model->update($data);

        return $this->success(
            __('User :name updated successfully', ['name' => $model->name]),
        );
    }

    public function destroy(string $id): JsonResponse|RedirectResponse
    {
        $model = User::find($id);

        abort_if($model === null, 404, __('User not found'));

        $model->delete();

        return $this->success(
            __('User :name deleted successfully', ['name' => $model->name]),
        );
    }

    public function bulk(BulkActionsRequest $request): JsonResponse|RedirectResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        switch ($action) {
            case 'delete':
                User::whereIn('id', $ids)->where('is_super_admin', '!=', true)->delete();
                return $this->success(__('Selected users deleted successfully'));
            case 'activate':
                User::whereIn('id', $ids)->where('is_super_admin', '!=', true)->update(['status' => UserStatus::ACTIVE]);
                return $this->success(__('Selected users activated successfully'));
            case 'deactivate':
                User::whereIn('id', $ids)->where('is_super_admin', '!=', true)->update(['status' => UserStatus::INACTIVE]);
                return $this->success(__('Selected users deactivated successfully'));
            case 'banned':
                User::whereIn('id', $ids)->where('is_super_admin', '!=', true)->update(['status' => UserStatus::BANNED]);
                return $this->success(__('Selected users banned successfully'));
            default:
                return $this->error(__('Invalid action'));
        }
    }
}
