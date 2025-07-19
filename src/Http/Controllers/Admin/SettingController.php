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

        $locale = $this->getFormLanguage();

        return view('core::admin.setting.index', compact('locale'));
    }

    public function socialLogin()
    {
        Breadcrumb::add(__('Social Login Setting'));

        $drivers = collect(config('core.social_login.providers', []))->keys()
            ->mapWithKeys(function ($driver) {
                return [$driver => title_from_key($driver)];
            });

        return view('core::admin.setting.social-login', compact('drivers'));
    }

    public function update(SettingRequest $request)
    {
        app(Setting::class)->sets($request->safe()->all());

        return $this->success(__('Setting updated successfully'));
    }
}
