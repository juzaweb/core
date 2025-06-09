<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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
