<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Output\StreamOutput;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\Module;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;

class ModuleController extends AdminController
{
    public function index()
    {
        Breadcrumb::add(__('core::translation.modules'));

        $modules = collect(Module::all())->filter(
            fn($module) => $module->getName() !== 'Admin'
        );

        return view(
            'core::admin.module.index',
            compact('modules')
        );
    }

    public function install(Request $request)
    {
        $request->validate(['module' => 'required']);

        $module = Module::find($request->input('module'));

        if (!$module) {
            return $this->error(__('Module :name not found', ['name' => $request->input('module')]));
        }

        return response()->stream(function () use ($module) {
            $stream = fopen('php://output', 'w');
            $output = new StreamOutput($stream);
            Artisan::call('module:install', ['name' => $module->getComposerAttr('name')], $output);
            $output->write('Module enabled successfully.');
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-cache',
        ]);
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

                Artisan::call('module:publish', [
                    'module' => $module,
                ]);

                Artisan::call('migrate', [
                    '--force' => true,
                ]);
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

    public function marketplace()
    {
        Breadcrumb::add(__('core::translation.modules'), action([self::class, 'index']));

        Breadcrumb::add(__('core::translation.marketplace'));

        return view('core::admin.module.marketplace');
    }

    public function loadMarketplaceData(Request $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        if ($limit > 20) {
            $limit = 20;
        }

        try {
            $response = Http::timeout(10)->get('https://juzaweb.com/api/modules', [
                'page' => $page,
                'per_page' => $limit,
            ]);

            if (!$response->successful()) {
                return $this->error(__('core::translation.failed_to_fetch_marketplace_data'));
            }

            $data = $response->json();
            $modules = $data['data'] ?? [];

            $total = $data['meta']['total'] ?? 0;

            // Get all installed modules
            $installedModules = collect(Module::all())->map(
                fn ($module) => $module->getComposerAttr('name')
            )->toArray();

            return $this->success(
                [
                    'html' => view(
                        'core::admin.module.components.marketplace-list',
                        compact('modules', 'installedModules')
                    )->render(),
                    'total' => $total,
                ]
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
