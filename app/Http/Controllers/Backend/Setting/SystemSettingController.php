<?php

namespace Juzaweb\Core\Http\Controllers\Backend\Setting;

use Juzaweb\Core\Http\Controllers\BackendController;
use Juzaweb\Core\Models\Config;
use Illuminate\Http\Request;

class SystemSettingController extends BackendController
{
    public function index($form = 'general')
    {
        $forms = $this->getForms();
        
        return view('juzaweb::backend.setting.system.index', [
            'title' => trans('juzaweb::app.system_setting'),
            'component' => $form,
            'forms' => $forms,
        ]);
    }
    
    public function save(Request $request)
    {
        $configs = $request->only($this->getSettings());
        foreach ($configs as $key => $config) {
            if ($request->has($key)) {
                Config::setConfig($key, $config);
            }
        }
    
        $form = $request->post('form');
        if (empty($form)) {
            $form = 'general';
        }
        
        return $this->success([
            'message' => trans('juzaweb::app.saved_successfully'),
            'redirect' => route('admin.setting.form', [$form]),
        ]);
    }

    protected function getForms()
    {
        $items = [
            'general' => [
                'name' => trans('juzaweb::app.general_setting'),
                'view' => 'juzaweb::backend.setting.system.form.general'
            ],
            'recaptcha' => [
                'name' => trans('juzaweb::app.google_recaptcha'),
                'view' => 'juzaweb::backend.setting.system.form.recaptcha'
            ]
        ];

        return apply_filters('admin.general_settings.forms', $items);
    }

    protected function getSettings()
    {
        $items = Config::getConfigs();

        return apply_filters('admin.setting_fields', $items);
    }
}
