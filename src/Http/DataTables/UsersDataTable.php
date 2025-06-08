<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Juzaweb\Core\DataTables\Action;
use Juzaweb\Core\DataTables\Column;
use Juzaweb\Core\DataTables\ColumnEditer;
use Juzaweb\Core\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;

class UsersDataTable extends DataTable
{
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::make('name'),
            Column::make('email'),
            Column::createdAt(),
            Column::actions(),
        ];
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->rawColumns($this->rawColumns)
            ->addColumn(
                'checkbox',
                function ($row) {
                    return '<input type="checkbox" name="rows[]" value="' . $row->id . '">';
                }
            )
            ->editColumn(
                'created_at',
                fn (User $user) => $user->created_at?->format('Y-m-d H:i:s')
            )
            ->editColumn(
                'actions',
                fn (User $user) => ColumnEditer::actions(
                    $user,
                    [
                        Action::edit(admin_url("users/{$user->id}/edit")),
                        Action::delete(),
                    ]
                )
            );
    }
}
