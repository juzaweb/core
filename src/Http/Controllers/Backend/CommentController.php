<?php

namespace Juzaweb\Core\Http\Controllers\Backend;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Juzaweb\Core\Http\Controllers\BackendController;
use Juzaweb\Core\Traits\ResourceController;
use Juzaweb\Core\Models\Comment;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class CommentController extends BackendController
{
    use ResourceController;

    use ResourceController {
        ResourceController::getDataForIndex as traitDataForIndex;
    }

    protected $viewPrefix = 'juzaweb::backend.comment';

    protected function validator(array $attributes)
    {
        $statuses = array_keys(Comment::allStatuses());

        $validator = Validator::make($attributes, [
            'email' => 'required|email',
            'name' => 'nullable',
            'website' => 'nullable',
            'content' => 'required',
            'status' => 'required|in:' . implode(',', $statuses),
        ]);

        return $validator;
    }

    protected function getModel()
    {
        return Comment::class;
    }

    protected function getTitle()
    {
        return trans('juzaweb::app.comments');
    }
    
    public function getDataTable(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'desc');
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 20);
        
        $query = Comment::query()->with(['user', 'postType']);

        if ($search) {
            $query->where(function (Builder $q) use ($search) {
                $q->orWhere('name', 'like', '%'. $search .'%');
                $q->orWhere('content', 'like', '%'. $search .'%');
            });
        }
        
        if ($status) {
            $query->where('status', '=', $status);
        }

        $count = $query->count();
        $query->orderBy($sort, $order);
        $query->offset($offset);
        $query->limit($limit);
        $rows = $query->get();
        
        foreach ($rows as $row) {
            $row->post = $row->postType->getDisplayName();
        }
        
        return response()->json([
            'total' => $count,
            'rows' => $rows
        ]);
    }

    protected function getDataForIndex()
    {
        $postType = $this->getPostType();
        $data = $this->traitDataForIndex();
        $data['postType'] = $postType;
        $data['postTypeSingular'] = Str::singular($postType);
        return $data;
    }

    protected function getPostType()
    {
        $split = explode('.', Route::currentRouteName());
        return Str::plural($split[count($split) - 3]);
    }
}
