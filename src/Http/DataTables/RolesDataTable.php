<?php

namespace Juzaweb\Modules\Core\Http\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Core\Permissions\Models\Role;

class RolesDataTable extends DataTable
{
    protected string $actionUrl = 'roles/bulk';

    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::editLink('name', admin_url('roles/{id}/edit'), __('core::translation.name')),
            Column::make('code', __('core::translation.code')),
            Column::make('guard_name', __('core::translation.guard_name')),
            Column::createdAt(),
            Column::actions(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('roles.delete'),
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("roles/{$model->id}/edit"))
                ->can('roles.edit'),
            Action::delete()
                ->can('roles.delete'),
        ];
    }
}
