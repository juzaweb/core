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

use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\DataTables\LanguagesDataTable;
use Juzaweb\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Core\Http\Requests\LanguageRequest;
use Juzaweb\Core\Models\Language;

class LanguageController extends AdminController
{
    public function index()
    {
        $dataTable = new LanguagesDataTable();

        Breadcrumb::add(__('Languages'));

        return $dataTable->render(
            'core::admin.language.index'
        );
    }

    public function store(LanguageRequest $request)
    {
        DB::transaction(
            function () use ($request) {
                Language::updateOrCreate(
                    ['code' => $request->input('code')],
                    [
                        ...$request->safe()->except(['code']),
                    ]
                );
            }
        );

        return $this->success(
            __('Language created successfully.')
        );
    }

    public function destroy(string $code)
    {
        $language = Language::where('code', $code)
            ->where('code', '!=', config('translatable.fallback_locale'))
            ->firstOrFail();

        $language->delete();

        return $this->success(
            __('Language deleted successfully.')
        );
    }

    public function bulk(BulkActionsRequest $request)
    {
        $ids = $request->input('ids', []);

        Language::whereIn('code', $ids)
            ->where('code', '!=', config('translatable.fallback_locale'))
            ->get()
            ->each(fn (Language $language) => $language->delete());

        return $this->success(
            __('Languages deleted successfully.')
        );
    }
}
