<?php

namespace Juzaweb\Modules\Core\Http\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Admin\Models\Documentation;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;

class DocumentationsDataTable extends DataTable
{
    protected string $actionUrl = 'documentations/bulk';

    public function query(Documentation $model): Builder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
			Column::checkbox(),
			Column::id(),
			Column::actions(),
			Column::editLink('title', admin_url('documentations/{id}/edit'), __('core::translation.label')),
			Column::make('code'),
			Column::make('module'),
			Column::make('active'),
			Column::createdAt(),
		];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("documentations/{$model->id}/edit"))->can('documentations.edit'),
            Action::delete()->can('documentations.delete'),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('documentations.delete'),
        ];
    }
}
