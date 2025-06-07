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
use Illuminate\Http\Request;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\UsersDataTable;

class UserController extends AdminController
{
    public function index(UsersDataTable $dataTable)
    {
        Breadcrumb::add('Users');

        return $dataTable->render(
            'core::admin.user.index',
            ['title' => __('Users')]
        );
    }

    public function create()
    {
        Breadcrumb::add(__('Users'), admin_url('users'));

        Breadcrumb::add(__('Add User'));

        $model = new User();

        return view(
            'core::admin.user.form',
            compact('model')
        );
    }

    public function edit(string $id)
    {
        $model = User::find($id);

        abort_if($model === null, 404, __('User not found'));

        Breadcrumb::add(__('Users'), admin_url('users'));

        Breadcrumb::add(__('Edit User: :name', ['name' => $model->name]));

        return view(
            'core::admin.user.form',
            compact('model')
        );
    }

    public function store(Request $request)
    {

    }
}
