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
use Juzaweb\Modules\Core\Facades\MenuBox;
use Juzaweb\Modules\Core\Facades\NavMenu;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\Requests\MenuRequest;
use Juzaweb\Modules\Core\Models\Menus\Menu;

class MenuController extends AdminController
{
    public function index(?string $id = null)
    {
        Breadcrumb::add(__('core::translation.menus'));

        $navMenus = NavMenu::all();
        $boxes = MenuBox::all();
        $locale = $this->getFormLanguage();
        $location = theme_setting('nav_location', []);

        if ($id) {
            $menu = Menu::withDataItems($locale)->findOrFail($id);
        } else {
            $menu = Menu::withDataItems($locale)->first();
        }

        if ($menu) {
            $this->setDefauLocaleItems($menu->items, $locale);
        }

        $menuDataUrl = route(
            'admin.load-data',
            [
                'token' => encrypt([
                    'model' => Menu::class,
                    'field' => 'name',
                ]),
            ]
        );

        return view(
            'core::admin.menu.index',
            compact(
                'menu',
                'navMenus',
                'menuDataUrl',
                'boxes',
                'locale',
                'location',
            )
        );
    }

    public function store(MenuRequest $request)
    {
        $model = Menu::create($request->all());

        return $this->success(
            [
                'message' => trans('core::translation.menu_name_created_successfully', ['name' => $model->name]),
                'redirect' => action([self::class, 'index'], [$model->id]),
            ]
        );
    }

    public function update(MenuRequest $request, string $id)
    {
        $model = Menu::findOrFail($id);
        $items = json_decode($request->post('content'), true, 512, JSON_THROW_ON_ERROR);
        $locale = $this->getFormLanguage();

        DB::transaction(
            function () use ($model, $request, $items, $locale) {
                $model->update($request->only(['name']));
                $index = 1;

                $results = $this->syncItems($model, $items, $index, $locale);

                $model->items()
                    ->where(
                        fn($q) => $q->whereNotIn('id', $results)
                            ->orWhereColumn('id', 'parent_id')
                    )
                    ->delete();

                if ($location = $request->post('location', [])) {
                    $locationConfig = theme_setting('nav_location');
                    foreach ($location as $item) {
                        $locationConfig[$item] = $model->id;
                    }

                    theme_setting()?->set('nav_location', $locationConfig);
                } else {
                    $location = collect(theme_setting('nav_location'))
                        ->filter(
                            fn($i) => $i != $model->id
                        )->toArray();

                    theme_setting()?->set('nav_location', $location);
                }

                do_action('admin.saved_menu', $model, $items);
            }
        );

        return $this->success(
            [
                'message' => trans('core::translation.menu_name_update_successfully', ['name' => $model->name]),
                'redirect' => action([self::class, 'index'], [$model->id, 'locale' => $locale]),
            ]
        );
    }

    public function destroy(string $id)
    {
        $model = Menu::findOrFail($id);
        $model->delete();

        return $this->success(
            [
                'message' => trans('core::translation.menu_name_deleted_successfully', ['name' => $model->name]),
                'redirect' => action([self::class, 'index']),
            ]
        );
    }

    protected function syncItems(
        Menu $model,
        array $items,
        int $index,
        string $locale,
        ?string $parentId = null
    ): array {

        $results = [];
        foreach ($items as $item) {
            if (isset($item['key'])) {
                $box = MenuBox::get($item['key']);
                $data = [
                    'menuable_type' => $box['class'],
                    'menuable_id' => $item['menuable_id'],
                    'parent_id' => $parentId,
                    'display_order' => $index,
                    'website_id' => $model->website_id,
                    'box_key' => $item['key'],
                    'target' => $item['target'] ?? '_self',
                    $locale => [
                        'label' => $item['label'],
                    ],
                ];
            } else {
                $data = [
                    'is_home' => $item['is_home'] ?? 0,
                    'link' => $item['link'],
                    'parent_id' => $parentId,
                    'display_order' => $index,
                    'website_id' => $model->website_id,
                    'box_key' => 'custom',
                    'target' => $item['target'] ?? '_self',
                    $locale => [
                        'label' => $item['label'],
                    ],
                ];
            }

            $newItem = $model->items()->updateOrCreate(
                [
                    'id' => $item['id'] ?? null,
                ],
                $data
            );

            $results[] = $newItem->id;

            if ($children = Arr::get($item, 'children')) {
                $results = array_merge(
                    $results,
                    $this->syncItems($model, $children, 1, $locale, $newItem->id)
                );
            }

            $index++;
        }

        return $results;
    }

    protected function setDefauLocaleItems($items, $locale): void
    {
        foreach ($items as $item) {
            $item->setDefaultLocale($locale);

            if ($item->children->isNotEmpty()) {
                $this->setDefauLocaleItems($item->children, $locale);
            }
        }
    }
}
