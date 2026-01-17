<?php

namespace Juzaweb\Modules\Core\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Core\Models\Comment;
use Yajra\DataTables\EloquentDataTable;
use function Juzaweb\Modules\Admin\Http\DataTables\website;

class CommentsDataTable extends DataTable
{
    protected string $actionUrl = 'comments/bulk';

    protected string $commentableType;

    public function forCommentableType(string $model): static
    {
        $this->commentableType = $model;

        return $this;
    }

    public function query(Comment $model): Builder
    {
        return $model->newQuery()
            ->with([
                'commentable' => fn ($query) => $query->withTranslation()->with(['media']),
                'commented'
            ])
            ->where('commentable_type', $this->commentableType);
    }

    public function getColumns(): array
    {
        return [
			Column::checkbox(),
			Column::id(),
			Column::actions(),
			Column::computed('content', __('core::translation.content')),
			Column::computed('commentable', __('core::translation.commented_on')),
            Column::computed('commented', __('core::translation.commented_by')),
			Column::make('status', __('core::translation.status')),
			Column::createdAt(),
		];
    }

    public function actions(Model $model): array
    {
        return [
            Action::delete()->can('comments.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::make(__('core::translation.approve'), icon: 'fas fa-check')->can('comments.approve')->action('approved'),
            BulkAction::make(__('core::translation.reject'), icon: 'fas fa-times')->can('comments.reject')->action('rejected'),
            BulkAction::delete()->can('comments.delete'),
        ];
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        return $builder
            ->editColumn('status', function (Comment $model) {
                return $model->status?->label();
            })
            ->editColumn('commentable', function (Comment $model) {
                $commentable = $model->commentable;
                if ($commentable) {
                    $title = $commentable->title ?? $commentable->name ?? __('core::translation.no_title');
                    $url = parse_url($commentable->getUrl(), PHP_URL_PATH);
                    $url = website()->url . $url;

                    return '<a href="' . $url . '" target="_blank">' . e($title) . '</a>';
                }

                return __('core::translation.deleted');
            })
            ->editColumn('commented', function (Comment $model) {
                $commented = $model->commented;
                if ($commented) {
                    $name = $commented->name ?? $commented->email;
                    return e($name);
                }
                return __('core::translation.deleted');
            })
            ->rawColumns(['commentable']);
    }
}
