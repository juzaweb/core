<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable as BaseDataTable;

abstract class DataTable extends BaseDataTable
{
    protected string $dom = '<"table-responsive"rt><"row"<"col-md-5"l><"col-md-7"p>><"clear">';

    protected array $rawColumns = ['actions', 'checkbox'];

    protected string $id = 'jw-datatable';

    protected string $rowId = 'id';

    protected int|array $orderBy = 1;

    abstract public function getColumns(): array;

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->dom($this->dom)
            ->setTableId($this->id)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy($this->orderBy)
            ->selectStyleSingle()
            ->bulkActions($this->bulkActions());
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->setRowId('id')
            ->rawColumns($this->rawColumns)
            ->editColumn(
                'checkbox',
                function ($row) {
                    return '<input type="checkbox" name="rows[]" class="jw-datatable-checkbox" value="' . $row->id . '">';
                }
            )
            ->editColumn(
                'created_at',
                fn (Model $user) => $user->created_at?->format('Y-m-d H:i:s')
            )
            ->editColumn(
                'actions',
                fn (Model $user) => ColumnEditer::actions(
                    $user,
                    [
                        Action::edit(admin_url("users/{$user->id}/edit")),
                        Action::delete(),
                    ]
                )
            );
    }

    /**
     * Get DataTables Html Builder instance.
     */
    public function builder(): HtmlBuilder
    {
        if (method_exists($this, 'htmlBuilder')) {
            return $this->htmlBuilder = $this->htmlBuilder();
        }

        if (! $this->htmlBuilder) {
            $this->htmlBuilder = app(HtmlBuilder::class);
        }

        return $this->htmlBuilder;
    }

    public function bulkActions(): array
    {
        return [];
    }
}
