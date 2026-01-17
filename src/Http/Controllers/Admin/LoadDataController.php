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
use Juzaweb\Modules\Core\Facades\MenuBox;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable;

class LoadDataController extends AdminController
{
    public function load(Request $request): JsonResponse
    {
        $search = $request->get('q');
        $explodes = $request->get('explodes');

        try {
            $token = decrypt($request->get('token'));
        } catch (\Exception $e) {
            return response()->json(['results' => []]);
        }

        $field = $token['field'] ?? 'name';
        $query = $token['model']::query();

        if ($search) {
            $query->search($search);
        }

        if ((new $token['model']) instanceof Translatable) {
            $query->withTranslation();
        }

        if ($explodes) {
            $query->whereNotIn('id', $explodes);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $paginate->map(
            function ($item) use ($field) {
                return [
                    'id' => $item->id,
                    'text' => $item->{$field},
                ];
            }
        );

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        return response()->json($data);
    }

    public function loadForMenu(Request $request): JsonResponse
    {
        $search = $request->get('q');

        try {
            $token = decrypt($request->get('token'));
        } catch (\Exception $e) {
            return response()->json(['results' => []]);
        }

        $box = MenuBox::get($token['box']);
        $options = $box['options']();
        $field = $options['field'] ?? 'name';
        $query = $box['class']::query()
            ->whereInMenuBox()
            ->latest()
            ->when(
                $search,
                fn ($q) => $q->search($search)
            );

        $paginate = $query->paginate(10);

        $data['results'] = $paginate->map(
            function ($item) use ($field) {
                return [
                    'id' => $item->id,
                    'text' => $item->{$field},
                    'edit_url' => $item->getEditUrl(),
                    'menuable_class_name' => class_basename($item),
                    'menuable_class' => get_class($item),
                ];
            }
        );
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        return response()->json($data);
    }
}
