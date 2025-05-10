<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;

class SettingController extends AdminController
{
    public function index()
    {
        Breadcrumb::add('General Setting');

        return view(
            'core::admin.setting.index',
            ['title' => __('General Setting')]
        );
    }
}
