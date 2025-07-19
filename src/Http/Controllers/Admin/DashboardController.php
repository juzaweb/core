<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use Juzaweb\Core\Facades\Dashboard;
use Juzaweb\Core\Http\Controllers\AdminController;

class DashboardController extends AdminController
{
    public function index()
    {
        $boxes = Dashboard::boxes();

        return view(
            'core::admin.dashboard.index',
            [
                'title' => __('video-sharing::translation.dashboard'),
                ...compact('boxes')
            ]
        );
    }
}
