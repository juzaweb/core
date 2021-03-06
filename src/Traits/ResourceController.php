<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 6/9/2021
 * Time: 2:05 PM
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ResourceController
{
    public function index()
    {
        return view($this->viewPrefix . '.index', $this->getDataForIndex());
    }

    public function create()
    {
        $this->addBreadcrumb([
            'title' => $this->getTitle(),
            'url' => action([static::class, 'index']),
        ]);

        $model = $this->makeModel();
        return view($this->viewPrefix . '.form', array_merge([
            'title' => trans('juzaweb::app.add_new')
        ], $this->getDataForForm($model)));
    }

    public function edit($id)
    {
        $this->addBreadcrumb([
            'title' => $this->getTitle(),
            'url' => action([static::class, 'index']),
        ]);

        $model = $this->makeModel()->findOrFail($id);
        return view($this->viewPrefix . '.form', array_merge([
            'title' => $model->{$model->getFieldName()}
        ], $this->getDataForForm($model)));
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        DB::beginTransaction();
        try {
            $this->beforeStore($request);
            $model = $this->getModel()::create($request->all());
            $this->afterStore($request, $model);
            $this->afterSave($request, $model);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->success([
            'message' => trans('juzaweb::app.created_successfully'),
            'redirect' => action([static::class, 'index'])
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validator($request->all())->validate();
        $model = $this->makeModel()->findOrFail($id);
        DB::beginTransaction();
        try {
            $this->beforeUpdate($request, $model);
            $model->update($request->all());
            $this->afterUpdate($request, $model);
            $this->afterSave($request, $model);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $this->success([
            'message' => trans('juzaweb::app.updated_successfully')
        ]);
    }

    public function bulkActions(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'action' => 'required',
        ]);

        $action = $request->post('action');
        $ids = $request->post('ids');

        foreach ($ids as $id) {
            switch ($action) {
                case 'delete':
                    $this->makeModel()->find($id)->delete($id);
                    break;
                default:
                    $this->makeModel()->find($id)->update([
                        'status' => $action
                    ]);
                    break;
            }
        }

        return $this->success([
            'message' => trans('juzaweb::app.successfully')
        ]);
    }

    public function getDataTable(Request $request)
    {
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);

        $query = $this->makeModel()->newQuery();
        $query->filter($request->all());

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();

        foreach ($rows as $row) {
            $row->edit_url = route('admin.design.sliders.edit', [$row->id]);
        }

        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    protected function beforeStore(Request $request)
    {
        //
    }

    protected function afterStore(Request $request, $model)
    {
        //
    }

    protected function beforeUpdate(Request $request, $model)
    {
        //
    }

    protected function afterUpdate(Request $request, $model)
    {
        //
    }

    protected function afterSave(Request $request, $model)
    {
        //
    }

    protected function makeModel()
    {
        return app($this->getModel());
    }

    protected function parseDataForSave(array $attributes)
    {
        return $attributes;
    }

    /**
     * Get data for form
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return array
     * */
    protected function getDataForForm($model)
    {
        return [
            'model' => $model
        ];
    }

    protected function getDataForIndex()
    {
        return [
            'title' => $this->getTitle()
        ];
    }

    /**
     * Validator for store and update
     *
     * @param array $attributes
     * @return \Illuminate\Support\Facades\Validator
     * */
    abstract protected function validator(array $attributes);

    /**
     * Get model resource
     *
     * @return \Illuminate\Database\Eloquent\Model
     * */
    abstract protected function getModel();

    /**
     * Get title resource
     *
     * @return string
     **/
    abstract protected function getTitle();
}