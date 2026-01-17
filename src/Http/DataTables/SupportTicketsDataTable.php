<?php

namespace Juzaweb\Modules\Core\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\SupportTicket\Models\SupportTicket;
use Yajra\DataTables\EloquentDataTable;

class SupportTicketsDataTable extends DataTable
{
    protected array $rawColumns = ['subject', 'actions'];

    public function query(SupportTicket $model): Builder
    {
        return $model->newQuery()
            ->with(['category'])
            ->withoutGlobalScope('website_id')
            ->where('website_id', config('network.main_website_id'))
            ->ofUser($this->request->user())
            ->with(['category']);
    }

    public function getColumns(): array
    {
        return [
            Column::editLink('subject', 'my-support-tickets/{id}/show', __('admin::translation.subject')),
            Column::computed('category')->title(__('admin::translation.category')),
            Column::make('status')->title(__('admin::translation.status')),
            Column::createdAt(),
        ];
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        return parent::renderColumns($builder)->editColumn(
            'category',
            function (SupportTicket $model) {
                return $model->category->name ?? __('admin::translation.uncategorized');
            }
        );
    }
}
