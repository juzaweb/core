<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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

    public function socialLogin()
    {
        Breadcrumb::add(__('Social Login Setting'));

        $drivers = [
            'facebook' => 'Facebook',
            'google' => 'Google',
            'github' => 'GitHub',
            'twitter' => 'Twitter',
            'linkedin' => 'LinkedIn',
            // 'apple' => 'Apple',
        ];

        return view('core::admin.setting.social-login', compact('drivers'));
    }

    public function update(SettingRequest $request)
    {
        app(Setting::class)->sets($request->safe()->all());

        return $this->success(__('Setting updated successfully'));
    }
}
