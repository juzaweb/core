<?php

namespace Juzaweb\Modules\Core\Http\Controllers\Admin;

use Juzaweb\Modules\Blog\Models\Post;
use Juzaweb\Modules\Core\Enums\CommentStatus;
use Juzaweb\Modules\Core\Facades\Breadcrumb;
use Juzaweb\Modules\Core\Http\Controllers\AdminController;
use Juzaweb\Modules\Core\Http\DataTables\CommentsDataTable;
use Juzaweb\Modules\Core\Http\Requests\CommentActionsRequest;
use Juzaweb\Modules\Core\Models\Comment;

class CommentController extends AdminController
{
    protected string $commentableType = Post::class;

    public function index(CommentsDataTable $dataTable)
    {
        Breadcrumb::add(__('core::translation.comments'));

        return $dataTable->forCommentableType($this->commentableType)->render(
            'core::comment.index',
            []
        );
    }

    public function bulk(CommentActionsRequest $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        $models = Comment::whereIn('id', $ids)
            ->where('commentable_type', $this->commentableType)
            ->get();

        foreach ($models as $model) {
            if ($action === 'approved') {
                $model->update(['status' => CommentStatus::APPROVED]);
            }

            if ($action === 'rejected') {
                $model->update(['status' => CommentStatus::REJECTED]);
            }

            if ($action === 'delete') {
                $model->delete();
            }
        }

        return $this->success([
            'message' => __('core::translation.bulk_action_performed_successfully'),
        ]);
    }
}
