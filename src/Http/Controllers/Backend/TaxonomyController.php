<?php

namespace Juzaweb\Core\Http\Controllers\Backend;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Juzaweb\Core\Http\Controllers\BackendController;
use Juzaweb\Core\Models\Taxonomy;

class TaxonomyController extends BackendController
{
    public function index($taxonomy)
    {
        $setting = $this->getSetting($taxonomy);
        $model = new Taxonomy();

        return view('juzaweb::backend.taxonomy.index', [
            'title' => $setting->get('label'),
            'setting' => $setting,
            'model' => $model,
            'taxonomy' => $taxonomy,
        ]);
    }

    public function create($taxonomy)
    {
        $setting = $this->getSetting($taxonomy);
        $model = new Taxonomy();
        $this->addBreadcrumb([
            'title' => $setting->get('label'),
            'url' => route('admin.' . $setting->get('type') . '.taxonomy.index', [$taxonomy])
        ]);

        return view('juzaweb::backend.taxonomy.form', [
            'model' => $model,
            'title' => trans('juzaweb::app.add_new'),
            'taxonomy' => $taxonomy,
            'setting' => $setting
        ]);
    }

    public function edit($taxonomy, $id)
    {
        $setting = $this->getSetting($taxonomy);
        $model = Taxonomy::find($id);
        $model->load('parent');

        $this->addBreadcrumb([
            'title' => $setting->get('label'),
            'url' => route('admin.'. $setting->get('type') .'.taxonomy.index', [$taxonomy])
        ]);

        return view('juzaweb::backend.taxonomy.form', [
            'model' => $model,
            'title' => $model->name,
            'taxonomy' => $taxonomy,
            'setting' => $setting
        ]);
    }

    public function getDataTable(Request $request, $taxonomy)
    {
        $setting = $this->getSetting($taxonomy);
        $search = $request->get('search');
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = Taxonomy::query();
        $query->where('taxonomy', '=', $setting->get('taxonomy'));
        $query->where('post_type', '=', $setting->get('post_type'));

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->where('name', 'like', '%'. $search .'%');
                $q->orWhere('description', 'like', '%'. $search .'%');
            });
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route("admin.{$setting->get('type')}.taxonomy.edit", [$taxonomy, $row->id]);
            $row->thumbnail = upload_url($row->thumbnail);
            $row->description = Str::words($row->description, 20);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    public function store(Request $request, $taxonomy)
    {
        $model = Taxonomy::create(array_merge($request->all(), [
            'post_type' => $this->getPostType(),
            'taxonomy' => $taxonomy
        ]));

        return $this->success([
            'message' => trans('juzaweb::app.successfully'),
            'html' => view('juzaweb::components.tag-item', [
                'item' => $model,
                'name' => $taxonomy,
            ])->render()
        ]);
    }

    public function update(Request $request, $taxonomy, $id)
    {
        $tax = Taxonomy::findOrFail($id);

        $tax->update(array_merge($request->all(), [
            'post_type' => $this->getPostType(),
            'taxonomy' => $taxonomy
        ]));

        return $this->success([
            'message' => trans('juzaweb::app.successfully')
        ]);
    }

    public function bulkActions(Request $request, $taxonomy)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required',
        ]);

        do_action('bulk_action.taxonomy.' . $taxonomy, $request->post());

        $action = $request->post('action');
        $ids = $request->post('ids');

        switch ($action) {
            case 'delete':
                Taxonomy::destroy($ids);
                break;
        }

        return $this->success([
            'message' => trans('juzaweb::app.successfully')
        ]);
    }

    public function getTagComponent(Request $request, $taxonomy)
    {
        $item = Taxonomy::findOrFail($request->input('id'));
        return $this->response([
            'html' => view('juzaweb::components.tag-item', [
                'item' => $item,
                'name' => $taxonomy
            ])
                ->render()
        ], true);
    }

    protected function getPostType()
    {
        $split = explode('.', Route::currentRouteName());
        return Str::plural($split[count($split) - 3]);
    }

    /**
     * Get taxonomy setting
     *
     * @param string $taxonomy
     * @return \Illuminate\Support\Collection
     **/
    protected function getSetting($taxonomy)
    {
        $taxonomies = apply_filters('juzaweb.taxonomies', []);
        return $taxonomies[$this->getPostType()][$taxonomy] ?? [];
    }
}
