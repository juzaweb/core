<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;

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
}
