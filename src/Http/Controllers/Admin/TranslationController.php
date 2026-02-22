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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Facades\Module;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\Requests\TranslateModelRequest;
use Juzaweb\Modules\Core\Http\Requests\TranslationRequest;
use Juzaweb\Modules\Core\Translations\Contracts\Translation;
use Juzaweb\Modules\Core\Translations\Models\Language;
use Juzaweb\Modules\Core\Translations\Models\LanguageLine;
use Yajra\DataTables\Facades\DataTables;

class TranslationController extends AdminController
{
    public function __construct(protected Translation $translationManager) {}

    public function index(string $locale)
    {
        $language = Language::findCode($locale);

        abort_if($language === null, 404, __('core::translation.language_not_found'));

        Breadcrumb::add(__('core::translation.languages'), action([LanguageController::class, 'index']));

        Breadcrumb::add(__('core::translation.phrases_language', ['language' => $language->name]));

        return view(
            'core::admin.translation.index',
            [
                'title' => $language,
                'locale' => $locale,
            ]
        );
    }

    public function update(TranslationRequest $request, string $locale)
    {
        $group = $request->post('group');
        $value = $request->post('value');
        $namespace = $request->post('namespace');
        $key = $request->post('key');

        $model = LanguageLine::firstOrNew(
            [
                'namespace' => $namespace,
                'group' => $group,
                'key' => $key,
            ]
        );

        $model->setTranslation($locale, $value);
        $model->save();

        return $this->success(__('core::translation.translation_updated_successfully'));
    }

    public function getDataCollection(string $locale): JsonResponse
    {
        $modules = collect(Module::allEnabled())->map(fn($item) => $item->getAliasName())->toArray();
        $theme = Theme::current();

        $collection = $this->translationManager->modules()
            ->filter(
                function ($module, $key) use ($modules, $theme) {
                    if ($module['type'] == 'module') {
                        return $key == 'admin' || in_array($key, $modules);
                    }

                    if ($module['type'] === 'theme') {
                        return $key === $theme->lowerName();
                    }

                    return true;
                }
            )
            ->map(
                function ($module, $key) {
                    return $this->translationManager->locale($key)
                        ->translationLines('en')
                        ->map(
                            function ($item) use ($module) {
                                $item['namespace'] = $module['namespace'] ?? '*';
                                return $item;
                            }
                        );
                }
            )
            ->filter(fn($item) => !empty($item))
            ->flatten(1);

        $langs = LanguageLine::get()
            ->keyBy(fn($item) => "{$item->namespace}-{$item->group}-{$item->key}");

        $items = $collection->map(
            function ($item) use ($langs, $locale) {
                $item['trans'] = $langs->get("{$item['namespace']}-{$item['group']}-{$item['key']}")
                    ->text[$locale] ?? $item['trans'];
                return $item;
            }
        );

        return DataTables::collection($items)->toJson();
    }

    public function translateModel(TranslateModelRequest $request): JsonResponse
    {
        abort_if(!config('translator.enable'), 404, __('core::translation.translation_feature_is_not_enabled'));

        $model = decrypt($request->post('model'));
        $ids = $request->post('ids');
        $locale = $request->post('locale');
        $source = $request->post('source', app()->getLocale());

        if ($locale === $source) {
            return $this->error(
                __('core::translation.source_and_target_language_must_be_different')
            );
        }

        if (! is_array($ids)) {
            $ids = [$ids];
        }

        $historyIds = [];

        DB::transaction(
            function () use ($model, $ids, $locale, $source, $request, &$historyIds) {
                $query = $model::query();

                if (method_exists($model, 'translations')) {
                    $query->with(
                        [
                            'translations' => fn($q) => $q->whereIn('locale', [$locale, $source]),
                        ]
                    );
                }

                $posts = $query->whereIn('id', $ids)->get();

                foreach ($posts as $post) {
                    $history = model_translate($post, $source, $locale);
                    $historyIds[] = $history->id;
                }
            }
        );

        return $this->success(
            [
                'message' => __('core::translation.translation_for_model_has_been_created'),
                'history_ids' => $historyIds,
            ]
        );
    }

    public function translateStatus(Request $request): JsonResponse
    {
        $historyIds = $request->post('history_ids', []);

        if (empty($historyIds)) {
            return response()->json(['error' => 'No history IDs provided'], 400);
        }

        $histories = \Juzaweb\Modules\Core\Translations\Models\TranslateHistory::whereIn('id', $historyIds)
            ->get(['id', 'status', 'error']);

        $pending = $histories->filter(fn($h) => $h->status->isPending())->count();
        $success = $histories->filter(fn($h) => $h->status->isSuccess())->count();
        $failed = $histories->filter(fn($h) => $h->status->isFailed())->count();

        $allCompleted = $pending === 0;

        return response()->json([
            'completed' => $allCompleted,
            'total' => $histories->count(),
            'pending' => $pending,
            'success' => $success,
            'failed' => $failed,
            'status' => $allCompleted ? 'completed' : 'processing',
        ]);
    }
}
