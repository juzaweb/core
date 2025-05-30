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

class DashboardController extends AdminController
{
    public function index()
    {
        return view('core::admin.dashboard.index', ['title' => __('Dashboard')]);
    }
}
