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
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Services\DataTable as BaseDataTable;

abstract class DataTable extends BaseDataTable
{
    protected string $dom = '<"table-responsive"rt><"row"<"col-md-5"l><"col-md-7"p>><"clear">';

    protected array $rawColumns = ['actions', 'checkbox'];

    protected string $tableClass = 'table-bordered table-hover';

    protected string $id = 'jw-datatable';

    protected string $rowId = 'id';

    protected int|array $orderBy = 1;

    protected string $actionUrl = '';

    abstract public function getColumns(): array;

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->dom($this->dom)
            ->addTableClass($this->tableClass)
            ->setTableId($this->id)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy($this->orderBy)
            ->selectStyleSingle()
            ->actionUrl($this->getActionUrl())
            ->bulkActions($this->bulkActions());
    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        $builder = (new EloquentDataTable($query))
            ->setRowId($this->rowId)
            ->rawColumns($this->rawColumns);

        if ($this->hasColumn('created_at')) {
            $builder->editColumn(
                'created_at',
                fn (Model $model) => $model->created_at?->toUserTimezone()->format('Y-m-d H:i:s')
            );
        }

        if ($this->hasColumn('updated_at')) {
            $builder->editColumn(
                'updated_at',
                fn (Model $model) => $model->updated_at?->toUserTimezone()->format('Y-m-d H:i:s')
            );
        }

        if ($this->hasCheckboxColumn()) {
            $builder->editColumn(
                'checkbox',
                function ($row) {
                    return ColumnEditer::checkbox($row);
                }
            );
        }

        if ($this->hasColumn('actions')) {
            $builder->editColumn(
                'actions',
                fn (Model $model) => ColumnEditer::actions(
                    $model,
                    $this->actions($model),
                    $this->getActionUrl()
                )
            );
        }

        if ($editColumn = $this->getColumn('edit')) {
            $builder->editColumn(
                $editColumn->name,
                fn (Model $model) => ColumnEditer::editLink($model, $this->getActionUrl(), $editColumn->name)
            );
        }

        return $builder;
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

    public function actions(Model $model): array
    {
        return [];
    }

    public function getActionUrl(): string
    {
        return Str::contains($this->actionUrl, 'http')
            ? $this->actionUrl
            : admin_url($this->actionUrl);
    }

    protected function hasCheckboxColumn(): bool
    {
        return $this->hasColumn('checkbox');
    }

    protected function hasColumn(string $name): bool
    {
        return $this->getColumn($name) !== null;
    }

    protected function getColumn(string $name): ?Column
    {
        return collect($this->getColumns())->filter(
            fn ($column) => $column->name === $name
        )->first();
    }
}
