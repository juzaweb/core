<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use ZipArchive;

class ThemeController extends AdminController
{
    public function index()
    {
        Breadcrumb::add(__('core::translation.themes'));

        $currentTheme = Theme::current();

        return view(
            'core::admin.theme.index',
            compact('currentTheme')
        );
    }

    public function loadData(Request $request)
    {
        $limit = $request->input('limit', 10);
        if ($limit > 20) {
            $limit = 20;
        }

        $currentTheme = Theme::current();
        $themes = Theme::all()->filter(fn($theme) => $theme->name() !== $currentTheme->name());

        return $this->success(
            [
                'html' => view(
                    'core::admin.theme.components.list',
                    compact('themes')
                )->render(),
                'total' => $themes->count(),
            ]
        );
    }

    public function activate(Request $request)
    {
        $themeName = $request->input('theme');
        $theme = Theme::find($themeName);

        if (! $theme) {
            return $this->error(__('core::translation.theme_not_found'));
        }

        DB::transaction(
            function () use ($themeName) {
                Theme::activate($themeName);
            }
        );

        return $this->success(__('core::translation.theme_activated_successfully'));
    }

    public function setting()
    {
        Breadcrumb::add(__('core::translation.theme_settings'));

        return view('core::admin.theme.setting');
    }

    /**
     * Install theme from uploaded zip file
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function installFromZip(Request $request): JsonResponse
    {
        $path = $request->input('path');
        $disk = 'tmp';

        try {
            // Validate input
            if (! $path) {
                return $this->error(__('core::translation.file_path_required'));
            }

            // Check if file exists in storage
            if (!Storage::disk($disk)->exists($path)) {
                return $this->error(__('core::translation.file_not_found'));
            }

            $fullPath = Storage::disk($disk)->path($path);

            // Create temporary extraction directory
            $tempDir = storage_path('app/tmp/' . uniqid('theme_extract_'));
            File::makeDirectory($tempDir, 0755, true);

            // Extract zip file
            $zip = new ZipArchive();
            if ($zip->open($fullPath) !== true) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.failed_to_open_zip_file'));
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // Find theme folder (usually the first directory in zip)
            $extractedItems = File::directories($tempDir);
            if (empty($extractedItems)) {
                // Check if files are directly in temp dir
                if (File::exists($tempDir . '/theme.json')) {
                    $themeDir = $tempDir;
                } else {
                    File::deleteDirectory($tempDir);
                    Storage::disk($disk)->delete($path);
                    return $this->error(__('core::translation.invalid_theme_structure'));
                }
            } else {
                $themeDir = $extractedItems[0];
            }

            // Validate theme.json exists
            $themeJsonPath = $themeDir . '/theme.json';
            if (!File::exists($themeJsonPath)) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.theme_json_not_found'));
            }

            // Parse and validate theme.json
            $themeJsonContent = File::get($themeJsonPath);
            $themeConfig = json_decode($themeJsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.invalid_theme_json_format'));
            }

            // Validate required fields in theme.json
            $requiredFields = ['name', 'version'];
            foreach ($requiredFields as $field) {
                if (empty($themeConfig[$field])) {
                    File::deleteDirectory($tempDir);
                    Storage::disk($disk)->delete($path);
                    return $this->error(__('core::translation.theme_json_missing_field', ['field' => $field]));
                }
            }

            $themeName = $themeConfig['name'];

            // Check if theme already exists
            $themesPath = base_path('themes/' . $themeName);
            if (File::exists($themesPath)) {
                File::deleteDirectory($tempDir);
                Storage::disk($disk)->delete($path);
                return $this->error(__('core::translation.theme_already_exists', ['name' => $themeName]));
            }

            // Validate composer.json if exists
            $composerJsonPath = $themeDir . '/composer.json';
            if (File::exists($composerJsonPath)) {
                $composerContent = File::get($composerJsonPath);
                json_decode($composerContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    File::deleteDirectory($tempDir);
                    Storage::disk($disk)->delete($path);
                    return $this->error(__('core::translation.invalid_composer_json_format'));
                }
            }

            // Move theme to themes directory
            File::ensureDirectoryExists(base_path('themes'));
            File::moveDirectory($themeDir, $themesPath);

            // Clean up
            File::deleteDirectory($tempDir);
            Storage::disk($disk)->delete($path);

            return $this->success(
                __('core::translation.theme_installed_successfully', ['name' => $themeName])
            );
        } catch (\Exception $e) {
            // Clean up on error
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
