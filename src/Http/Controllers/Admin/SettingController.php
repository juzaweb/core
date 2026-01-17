<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Juzaweb\Modules\Core\Contracts\Setting;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\Requests\SettingRequest;
use Juzaweb\Modules\Core\Http\Requests\TestMailRequest;
use Juzaweb\Modules\Core\Mail\Test;

class SettingController extends AdminController
{
    public function index()
    {
        Breadcrumb::add(__('core::translation.general_setting'));

        $locale = $this->getFormLanguage();

        return view('core::admin.setting.index', compact('locale'));
    }

    public function socialLogin()
    {
        Breadcrumb::add(__('core::translation.social_login_setting'));

        $drivers = collect(config('core.social_login.providers', []))->keys()
            ->mapWithKeys(function ($driver) {
                return [$driver => title_from_key($driver)];
            });

        return view('core::admin.setting.social-login', compact('drivers'));
    }

    public function email(Request $request)
    {
        Breadcrumb::add(__('core::translation.settings'), admin_url('settings/general'));

        Breadcrumb::add(__('core::translation.email_setting'));

        $user = $request->user();

        return view('core::admin.setting.email', compact('user'));
    }

    public function update(SettingRequest $request)
    {
        app(Setting::class)->sets($request->safe()->all());

        return $this->success(__('core::translation.setting_updated_successfully'));
    }

    public function testEmail(TestMailRequest $request)
    {
        $email = $request->input('email');

        Mail::to($email)->send(new Test());

        return $this->success(__('core::translation.mail_sent_successfully_check_your_inbox'));
    }
}
