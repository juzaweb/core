<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\PagesDataTable;
use Juzaweb\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Core\Http\Requests\PageRequest;
use Juzaweb\Core\Models\Enums\PageStatus;
use Juzaweb\Core\Models\Page;

class PageController extends AdminController
{
    public function index(PagesDataTable $dataTable)
    {
        Breadcrumb::add(__('Pages'));

        return $dataTable->render(
            'core::admin.page.index',
            []
        );
    }

    public function create(Request $request)
    {
        Breadcrumb::add(__('Pages'), admin_url('pages'));

        Breadcrumb::add(__('Add New Page'));

        $model = new Page();
        $action = action([static::class, 'store']);
        $locale = $request->get('locale', config('translatable.fallback_locale'));

        return view(
            'core::admin.page.form',
            compact('model', 'action', 'locale')
        );
    }

    public function edit(Request $request, string $id)
    {
        $locale = $this->getFormLanguage();

        $model = Page::withTranslation($locale)->find($id);

        abort_if($model === null, 404, __('video-sharing::translation.page_not_found'));

        Breadcrumb::add(__('Pages'), admin_url('pages'));

        Breadcrumb::add(__('Edit page :name', ['name' => $model->name]));

        $action = action([static::class, 'update'], ['id' => $model->id]);

        return view(
            'core::admin.page.form',
            compact('model', 'action', 'locale')
        );
    }

    public function store(PageRequest $request): JsonResponse|RedirectResponse
    {
        $data = $request->safe()->all();

        $model = Page::create($data);

        return $this->success(
            __('Created page :name successful', ['name' => $model->name]),
        );
    }

    public function update(PageRequest $request, string $id): JsonResponse|RedirectResponse
    {
        $model = Page::find($id);

        abort_if($model === null, 404, __('Page not found'));

        $data = $request->safe()->all();

        $model->update($data);

        return $this->success(
            [
                'message' => __('video-sharing::translation.page_name_updated_successfully', ['name' => $model->name]),
                'redirect' => action([static::class, 'edit'], ['id' => $model->id]),
            ]
        );
    }

    public function destroy(string $id): JsonResponse|RedirectResponse
    {
        $model = Page::find($id);

        abort_if($model === null, 404, __('Page not found'));

        $model->delete();

        return $this->success(
            __('video-sharing::translation.page_name_deleted_successfully', ['name' => $model->name]),
        );
    }

    public function bulk(BulkActionsRequest $request): JsonResponse|RedirectResponse
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        switch ($action) {
            case 'delete':
                Page::whereIn('id', $ids)->where('is_super_admin', '!=', true)->delete();
                return $this->success(__('video-sharing::translation.selected_pages_deleted_successfully'));
            case 'draft':
                Page::whereIn('id', $ids)->where('is_super_admin', '!=', true)->update(['status' => PageStatus::DRAFT]);
                return $this->success(__('video-sharing::translation.selected_pages_activated_successfully'));
            case 'publish':
                Page::whereIn('id', $ids)
                    ->where('is_super_admin', '!=', true)
                    ->update(['status' => PageStatus::PUBLISHED]);
                return $this->success(__('video-sharing::translation.selected_pages_deactivated_successfully'));
            default:
                return $this->error(__('video-sharing::translation.invalid_action'));
        }
    }
}
