<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\Module;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;

class ModuleController extends AdminController
{
    public function index()
    {
        Breadcrumb::add(__('core::translation.modules'));

        $modules = Module::all();

        return view(
            'core::admin.module.index',
            compact('modules')
        );
    }

    public function toggle(Request $request)
    {
        $request->validate(
            [
                'module' => 'required',
                'status' => 'required|in:0,1',
            ]
        );

        $module = $request->input('module');
        $status = $request->input('status');

        try {
            if ($status == 1) {
                Module::enable($module);
            } else {
                Module::disable($module);
            }

            return $this->success(
                [
                    'message' => __('core::message.save_successfully'),
                ]
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
