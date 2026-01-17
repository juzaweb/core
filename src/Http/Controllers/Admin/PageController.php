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

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Enums\PageStatus;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\PageTemplate;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\DataTables\PagesDataTable;
use Juzaweb\Modules\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Modules\Core\Http\Requests\PageRequest;
use Juzaweb\Modules\Core\Models\Pages\Page;

class PageController extends AdminController
{
    public function index(PagesDataTable $dataTable)
    {
        Breadcrumb::add(__('admin::translation.pages'));

        return $dataTable->render(
            'admin::admin.page.index',
            []
        );
    }

    public function create(Request $request, string $websiteId)
    {
        Breadcrumb::add(__('admin::translation.pages'), admin_url('pages'));

        Breadcrumb::add(__('admin::translation.add_new_page'));

        $model = new Page();
        $action = action([static::class, 'store'], [$websiteId]);
        $locale = $this->getFormLanguage();
        $templates = collect(PageTemplate::all())->map(fn ($item) => $item->label);

        if ($template = $request->get('template')) {
            $template = PageTemplate::get($template);
            $model->template = $template?->key;
        }

        return view(
            'admin::admin.page.form',
            compact('model', 'action', 'locale', 'templates', 'template')
        );
    }

    public function edit(Request $request, string $websiteId, string $id)
    {
        $locale = $this->getFormLanguage();

        $model = Page::withTranslation($locale, ['media'])->find($id);

        abort_if($model === null, 404, __('video-sharing::translation.page_not_found'));

        Breadcrumb::add(__('admin::translation.pages'), admin_url('pages'));

        $model->setDefaultLocale($locale);
        $model->load(['blocks' => function ($query) use ($locale) {
            $query->withTranslation($locale);
        }]);

        $model->blocks->each->setDefaultLocale($locale);

        Breadcrumb::add(__('admin::translation.edit_page_name', ['name' => $model->name]));

        $action = action([static::class, 'update'], [$websiteId, $model->id]);
        $templates = collect(PageTemplate::all())->map(fn ($item) => $item->label);

        $template = null;
        if ($model->template) {
            $template = PageTemplate::get($model->template);
        }

        if ($request->get('template')) {
            $template = PageTemplate::get($request->get('template'));
            $model->template = $template?->key;
        }

        return view(
            'admin::admin.page.form',
            compact('model', 'action', 'locale', 'templates', 'template')
        );
    }

    public function store(PageRequest $request, string $websiteId): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->all();
        $locale = $this->getFormLanguage();
        $containers = Arr::get($data, 'blocks', []);

        $model = DB::transaction(
            function () use ($data, $locale, $containers, $request) {
                $model = new Page($data);
                $model->setDefaultLocale($locale);
                $model->save();

                // Handle home page setting
                if ($request->boolean('is_home')) {
                    // Update theme setting
                    theme_setting()?->set('home_page', $model->id);
                }

                foreach ($containers as $containerKey => $container) {
                    $displayOrders = 0;
                    foreach ($container as $content) {
                        $model->blocks()->updateOrCreate(
                            [
                                'block' => $content['block'],
                                'container' => $containerKey,
                            ],
                            [
                                'data' => Arr::except($content, ['id', 'key', 'label', 'field']),
                                'display_order' => $displayOrders,
                                $locale => [
                                    'label' => $content['label'] ?? $content[$locale]['label'] ?? null,
                                    'fields' => Arr::except($content['field'] ?? [], ['label']),
                                ]
                            ]
                        );

                        $displayOrders++;
                    }
                }

                return $model;
            }
        );

        return $this->success(
            [
                'message' => __('admin::translation.created_page_name_successful', ['name' => $model->name]),
                'redirect' => action([static::class, 'index'], [$websiteId]),
            ]
        );
    }

    public function update(PageRequest $request, string $websiteId, string $id): JsonResponse|RedirectResponse
    {
        $model = Page::find($id);

        abort_if($model === null, 404, __('admin::translation.page_not_found'));

        $locale = $this->getFormLanguage();
        $data = $request->safe()->all();
        $containers = Arr::get($data, 'blocks', []);

        $model = DB::transaction(
            function () use ($data, $model, $locale, $containers, $request) {
                $model->setDefaultLocale($locale)->update($data);

                // Handle home page setting
                if ($request->boolean('is_home')) {
                    // Update theme setting
                    theme_setting()?->set('home_page', $model->id);
                }

                $blockIds = [];
                foreach ($containers as $containerKey => $container) {
                    $displayOrders = 1;
                    foreach ($container as $content) {
                        $block = $model->blocks()->updateOrCreate(
                            [
                                'id' => $content['id'] ?? null,
                                'container' => $containerKey,
                            ],
                            [
                                'block' => $content['block'],
                                'data' => Arr::except($content, ['id', 'key', 'label', 'field']),
                                'display_order' => $displayOrders,
                                $locale => [
                                    'label' => $content['label'] ?? $content[$locale]['label'] ?? null,
                                    'fields' => Arr::except($content['field'] ?? [], ['label']),
                                ]
                            ]
                        );

                        $blockIds[] = $block->id;
                        $displayOrders++;
                    }
                }

                $model->blocks()
                    ->whereNotIn('id', $blockIds)
                    ->get()
                    ->each
                    ->delete();

                return $model;
            }
        );

        return $this->success(
            [
                'message' => __('admin::translation.page_name_updated_successfully', ['name' => $model->name]),
                'redirect' => action([static::class, 'edit'], [$websiteId, $model->id]),
            ]
        );
    }

    public function destroy(string $websiteId, string $id): JsonResponse|RedirectResponse
    {
        $model = Page::find($id);

        abort_if($model === null, 404, __('admin::translation.page_not_found'));

        $model->delete();

        return $this->success(
            __('video-sharing::translation.page_name_deleted_successfully', ['name' => $model->name]),
        );
    }

    public function bulk(BulkActionsRequest $request, string $websiteId): JsonResponse|RedirectResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        switch ($action) {
            case 'delete':
                Page::whereIn('id', $ids)->get()->each->delete();
                return $this->success(__('video-sharing::translation.selected_pages_deleted_successfully'));
            case 'draft':
                Page::whereIn('id', $ids)->get()->each->update(['status' => PageStatus::DRAFT]);
                return $this->success(__('video-sharing::translation.selected_pages_activated_successfully'));
            case 'publish':
                Page::whereIn('id', $ids)
                    ->get()
                    ->each
                    ->update(['status' => PageStatus::PUBLISHED]);
                return $this->success(__('video-sharing::translation.selected_pages_deactivated_successfully'));
            default:
                return $this->error(__('video-sharing::translation.invalid_action'));
        }
    }
}
