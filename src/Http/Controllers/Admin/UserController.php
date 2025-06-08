<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use App\Models\User;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\UsersDataTable;
use Juzaweb\Core\Http\Requests\UserRequest;
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

    public function store(UserRequest $request)
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

    public function update(UserRequest $request, string $id)
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

    public function destroy(string $id)
    {
        $model = User::find($id);

        abort_if($model === null, 404, __('User not found'));

        $model->delete();

        return $this->success(
            __('User :name deleted successfully', ['name' => $model->name]),
        );
    }
}
