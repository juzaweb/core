<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\UsersDataTable;

class UserController extends AdminController
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('core::admin.user.index', ['title' => __('Users')]);
    }
}
