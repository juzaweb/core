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

use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\DataTables\LanguagesDataTable;
use Juzaweb\Modules\Core\Http\Requests\BulkActionsRequest;
use Juzaweb\Modules\Core\Http\Requests\LanguageRequest;
use Juzaweb\Modules\Core\Models\Language;

class LanguageController extends AdminController
{
    public function index(LanguagesDataTable $dataTable)
    {
        Breadcrumb::add(__('core::translation.languages'));

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
            __('core::translation.language_created_successfully')
        );
    }

    public function destroy(string $code)
    {
        $defaultLanguage = Language::default();

        $language = Language::where('code', $code)
            ->where('code', '!=', config('translatable.fallback_locale'))
            ->where('code', '!=', $defaultLanguage)
            ->firstOrFail();

        $language->delete();

        return $this->success(
            __('core::translation.language_deleted_successfully')
        );
    }

    public function bulk(BulkActionsRequest $request)
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action');
        $defaultLanguage = Language::default();

        if ($action === 'set-default') {
            if (count($ids) !== 1) {
                return $this->error(__('core::translation.please_select_exactly_one_language_to_set_as_default'));
            }

            $code = $ids[0];

            // Validate that the language exists before setting it as default
            $language = Language::where('code', $code)->firstOrFail();

            setting()->set('language', $code);
            return $this->success(__('core::translation.default_language_set_successfully'));
        }

        $languages = Language::whereIn('code', $ids)
            ->where('code', '!=', config('translatable.fallback_locale'))
            ->where('code', '!=', $defaultLanguage)
            ->get();

        $languages->each(fn(Language $language) => $language->delete());

        return $this->success(
            __('core::translation.languages_deleted_successfully')
        );
    }
}
