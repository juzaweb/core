<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\Module;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Support\Composer;
use ZipArchive;

class ModuleController extends AdminController
{
    public function __construct()
    {
        $this->middleware('permission:modules.index')->only(['index']);
        $this->middleware('permission:modules.edit')->only(['toggle']);
    }

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

    public function installFromZip(Request $request): JsonResponse
    {
        $path = $request->input('path');
        $disk = 'tmp';

        try {
            if (empty($path)) {
                return $this->error(__('core::translation.file_path_required'));
            }

            if (!Storage::disk($disk)->exists($path)) {
                return $this->error(__('core::translation.file_not_found'));
            }

            $fullPath = Storage::disk($disk)->path($path);

            $tempDir = storage_path('app/tmp/' . uniqid('module_extract_'));
            File::makeDirectory($tempDir, 0755, true);

            $zip = new ZipArchive();
            if ($zip->open($fullPath) !== true) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.failed_to_open_zip_file'));
            }

            $zip->extractTo($tempDir);
            $zip->close();

            $extractedItems = File::directories($tempDir);
            if (empty($extractedItems)) {
                if (File::exists($tempDir . '/module.json')) {
                    $moduleDir = $tempDir;
                } else {
                    File::deleteDirectory($tempDir);
                    Storage::disk($disk)->delete($path);
                    return $this->error(__('core::translation.invalid_module_structure'));
                }
            } else {
                $moduleDir = $extractedItems[0];
            }

            $moduleJsonPath = $moduleDir . '/module.json';
            if (!File::exists($moduleJsonPath)) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.module_json_not_found'));
            }

            $moduleJsonContent = File::get($moduleJsonPath);
            $moduleConfig = json_decode($moduleJsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.invalid_module_json_format'));
            }

            $requiredFields = ['name', 'version'];
            foreach ($requiredFields as $field) {
                if (empty($moduleConfig[$field])) {
                    File::deleteDirectory($tempDir);
                    Storage::disk($disk)->delete($path);
                    return $this->error(__('core::translation.module_json_missing_field', ['field' => $field]));
                }
            }

            $moduleName = $moduleConfig['name'];
            if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $moduleName)) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.invalid_module_name'));
            }

            $modulesPath = base_path('modules/' . $moduleName);

            if (File::exists($modulesPath)) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.module_already_exists', ['name' => $moduleName]));
            }

            File::ensureDirectoryExists(base_path('modules'));
            File::moveDirectory($moduleDir, $modulesPath);

            // Install dependencies
            if (File::exists($modulesPath . '/composer.json')) {
                app(Composer::class)->install($modulesPath);
            }

            File::deleteDirectory($tempDir);
            Storage::disk($disk)->delete($path);

            return $this->success(
                __('core::translation.module_installed_successfully', ['name' => $moduleName])
            );
        } catch (\Exception $e) {
            if (isset($tempDir) && File::exists($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            if (isset($path)) {
                Storage::disk($disk)->delete($path);
            }

            return $this->error($e->getMessage());
        }
    }
}
