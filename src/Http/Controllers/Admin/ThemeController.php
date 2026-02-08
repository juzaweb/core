<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
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

    public function marketplace()
    {
        Breadcrumb::add(__('core::translation.themes'), action([self::class, 'index']));

        Breadcrumb::add(__('core::translation.marketplace'));

        return view('core::admin.theme.marketplace');
    }

    public function loadMarketplaceData(Request $request): JsonResponse
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        if ($limit > 20) {
            $limit = 20;
        }

        try {
            $response = Http::timeout(10)->get('https://juzaweb.com/api/themes', [
                'page' => $page,
                'per_page' => $limit,
            ]);

            if (!$response->successful()) {
                return $this->error(__('core::translation.failed_to_fetch_marketplace_data'));
            }

            $data = $response->json();
            $themes = $data['data'] ?? [];

            $total = $data['meta']['total'] ?? 0;

            // Get all installed themes
            $installedThemes = collect(Theme::all())->map(fn ($theme) => $theme->getComposerAttr('name'))->toArray();

            return $this->success(
                [
                    'html' => view(
                        'core::admin.theme.components.marketplace-list',
                        compact('themes', 'installedThemes')
                    )->render(),
                    'total' => $total,
                ]
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
