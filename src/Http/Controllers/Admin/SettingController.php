<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use Juzaweb\Core\Contracts\Setting;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\Requests\SettingRequest;

class SettingController extends AdminController
{
    public function index()
    {
        Breadcrumb::add(__('General Setting'));

        return view('core::admin.setting.index');
    }

    public function update(SettingRequest $request)
    {
        app(Setting::class)->sets($request->safe()->all());

        return $this->success(__('Setting updated successfully'));
    }
}
