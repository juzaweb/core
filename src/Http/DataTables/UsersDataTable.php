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
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox('select_all'),
            Column::make('id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('created_at'),
            Column::computed('actions')
                ->addClass('text-center')
                ->width('200px'),
        ];
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->editColumn('created_at', fn (User $user) => $user->created_at?->format('Y-m-d H:i:s'))
            ->editColumn(
                'actions',
                fn (User $user) => view(
                    'core::components.datatables.actions',
                    [
                        'model' => $user,
                        'actions' => [
                            Action::edit(admin_url("users/{$user->id}/edit")),
                            Action::delete(),
                        ],
                    ]
                )
            );
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }
}
