<?php

namespace Juzaweb\Core\Http\Controllers\Backend;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Juzaweb\Core\Http\Controllers\BackendController;
use Juzaweb\Theme\Models\Menu;
use Juzaweb\Core\Models\User;
use Juzaweb\Core\Models\Taxonomy;
use Juzaweb\Core\Traits\ArrayPagination;
use Juzaweb\Theme\Models\Page;

class LoadDataController extends BackendController
{
    use ArrayPagination;

    public function loadData($func, Request $request) {
        if (method_exists($this, $func)) {
            return $this->{$func}($request);
        }
        
        return response()->json([
            'status' => 'error',
            'message' => 'Function not found',
        ]);
    }

    protected function loadTaxonomies(Request $request)
    {
        $search = $request->get('search');
        $explodes = $request->get('explodes');
        $postType = $request->get('post_type');
        $taxonomy = $request->get('taxonomy');

        $query = Taxonomy::query();
        $query->select([
            'id',
            'name as text'
        ])
            ->where('post_type', '=', $postType)
            ->where('taxonomy', '=', $taxonomy);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        if ($explodes) {
            $query->whereNotIn('id', $explodes);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        return response()->json($data);
    }
    
    protected function loadUsers(Request $request) {
        $search = $request->get('search');
        $explodes = $request->get('explodes');
        
        $query = User::query();
        $query->select([
            'id',
            'name AS text'
        ]);
        
        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', '%'. $search .'%');
                $q->orWhere('email', 'like', '%'. $search .'%');
            });
        }
        
        if ($explodes) {
            $query->whereNotIn('id', $explodes);
        }
        
        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        
        return response()->json($data);
    }
    
    protected function loadMenu(Request $request) {
        $search = $request->get('search');
        $explodes = $request->get('explodes');
        
        $query = Menu::query();
        $query->select([
            'id',
            'name AS text'
        ]);
        
        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->orWhere('name', 'like', '%'. $search .'%');
            });
        }
        
        if ($explodes) {
            $query->whereNotIn('id', $explodes);
        }
        
        $paginate = $query->paginate(10);
        $data['results'] = $query->get();
        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }
        
        return response()->json($data);
    }

    protected function loadLocales(Request $request)
    {
        $search = strtolower($request->get('search', ''));

        $results = collect(config('locales'));

        if ($search) {
            $results = $results->filter(function ($item) use ($search) {
                return strpos(strtolower($item['code']), $search) !== false ||
                    strpos(strtolower($item['name']), $search) !== false;
            });
        }

        $results = $results->map(function ($item) {
            return [
                'id' => $item['code'],
                'text' => $item['name']
            ];
        })->values()->toArray();

        $paginate = $this->arrayPaginate($results, 10);

        $data['results'] = $paginate->values();

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        return response()->json($data);
    }

    protected function loadPages(Request $request)
    {
        $search = $request->get('search');
        $explodes = $request->get('explodes');

        $query = Page::query();
        $query->select([
            'id',
            'name as text'
        ]);

        if ($search) {
            $query->where('name', 'like', '%'. $search .'%');
        }

        if ($explodes) {
            $query->whereNotIn('id', $explodes);
        }

        $paginate = $query->paginate(10);
        $data['results'] = $query->get();

        if ($paginate->nextPageUrl()) {
            $data['pagination'] = ['more' => true];
        }

        return response()->json($data);
    }
}
