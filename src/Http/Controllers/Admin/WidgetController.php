<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\Sidebar;
use Juzaweb\Modules\Core\Facades\Widget;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\Requests\WidgetRequest;
use Juzaweb\Modules\Core\Models\ThemeSidebar;

class WidgetController extends AdminController
{
    public function index()
    {
        Breadcrumb::add(__('core::translation.widgets'));

        $widgets = Widget::all();
        $sidebars = Sidebar::all();
        $locale = $this->getFormLanguage();
        $sidebarWidgets = ThemeSidebar::withTranslation($locale)
            ->orderBy('display_order')
            ->get()
            ->each(fn ($item) => $item->setDefaultLocale($locale))
            ->groupBy('sidebar');

        return view(
            'core::admin.widget.index',
            compact('widgets', 'sidebars', 'locale', 'sidebarWidgets')
        );
    }

    public function update(WidgetRequest $request, string $websiteId, string $key)
    {
        $contents = collect($request->input('content', []))->keyBy('key');
        $locale = $this->getFormLanguage();

        DB::transaction(
            function () use ($key, $contents, $locale) {
                $displayOrders = 1;
                $sidebarIds = [];
                foreach ($contents as $content) {
                    $sidebar = ThemeSidebar::updateOrCreate(
                        [
                            'id' => $content['id'] ?? null,
                        ],
                        [
                            'sidebar' => $key,
                            'widget' => $content['widget'],
                            'data' => Arr::except($content, ['id', 'key', 'widget', 'label', 'field']),
                            'display_order' => $displayOrders,
                            $locale => [
                                'label' => $content['label'] ?? $content[$locale]['label'] ?? null,
                                'fields' => Arr::except($content['field'] ?? [], ['label']),
                            ]
                        ]
                    );

                    $displayOrders++;
                    $sidebarIds[] = $sidebar->id;
                }

                ThemeSidebar::where('sidebar', $key)
                    ->whereNotIn('id', $sidebarIds)
                    ->delete();
            }
        );

        return $this->success(
            [
                'message' => __('core::translation.update_successfully'),
                'redirect' => route('admin.widgets.index', [$websiteId, 'locale' => $locale]),
            ]
        );
    }
}
