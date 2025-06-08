<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Core\DataTables\Action;
use Juzaweb\Core\DataTables\BulkAction;
use Juzaweb\Core\DataTables\Column;
use Juzaweb\Core\DataTables\DataTable;
use Juzaweb\Permissions\Models\Role;

class RolesDataTable extends DataTable
{
    protected string $actionUrl = 'roles/bulk';

    public function query(Role $model): QueryBuilder
    {
        return $model->newQuery()->filter(request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::make('name'),
            Column::createdAt(),
            Column::actions(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete(),
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("roles/{$model->id}/edit")),
            Action::delete(),
        ];
    }
}
