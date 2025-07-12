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

use Illuminate\Http\Request;
use Juzaweb\Core\Facades\Breadcrumb;
use Juzaweb\Core\Http\Controllers\AdminController;
use Juzaweb\Core\Http\Requests\TranslationRequest;
use Juzaweb\Core\Models\Language;
use Juzaweb\Translations\Contracts\Translation;
use Juzaweb\Translations\Models\LanguageLine;
use Yajra\DataTables\Facades\DataTables;

class TranslationController extends AdminController
{
    public function __construct(protected Translation $translationManager)
    {
    }

    public function index(string $locale)
    {
        $language = Language::find($locale);

        abort_if($language === null, 404, __('Language not found'));

        Breadcrumb::add(__('Languages'), action([LanguageController::class, 'index']));

        Breadcrumb::add(__('Phrases: :language', ['language' => $language->name]));

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

        return $this->success(__('Translation updated successfully'));
    }

    public function getDataCollection(string $locale): \Illuminate\Http\JsonResponse
    {
        $collection = $this->translationManager->modules()->map(
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

        $langs = LanguageLine::get(['key', 'text'])->keyBy(fn ($item) => "{$item->namespace}-{$item->group}-{$item->key}");

        $items = $collection->map(
            function ($item) use ($langs, $locale) {
                $item['trans'] = $langs->get("{$item['namespace']}-{$item['group']}-{$item['key']}")
                    ->text[$locale] ?? $item['trans'];
                return $item;
            }
        );

        return DataTables::collection($items)->toJson();
    }
}
