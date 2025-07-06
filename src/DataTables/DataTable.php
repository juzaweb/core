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

    /**
     * Create and configure an EloquentDataTable instance.
     *
     * @param QueryBuilder $query
     * @return EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        // Initialize EloquentDataTable with the query
        $builder = (new EloquentDataTable($query))
            ->setRowId($this->rowId)
            ->rawColumns($this->rawColumns);

        // Format 'created_at' column to user timezone if it exists
        if ($this->hasColumn('created_at')) {
            $builder->editColumn(
                'created_at',
                fn (Model $model) => $model->created_at?->toUserTimezone()->format('Y-m-d H:i:s')
            );
        }

        // Format 'updated_at' column to user timezone if it exists
        if ($this->hasColumn('updated_at')) {
            $builder->editColumn(
                'updated_at',
                fn (Model $model) => $model->updated_at?->toUserTimezone()->format('Y-m-d H:i:s')
            );
        }

        // Add checkbox column if it exists
        if ($this->hasCheckboxColumn()) {
            $builder->editColumn(
                'checkbox',
                function ($row) {
                    return ColumnEditer::checkbox($row, $this->rowId);
                }
            );
        }

        // Add actions column if it exists
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

        // Add edit link to the specified column if it exists
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

    /**
     * Get bulk actions.
     *
     * @return array
     */
    public function bulkActions(): array
    {
        return [];
    }

    /**
     * Get the list of actions for a given model.
     *
     * This method is intended to provide an array of actions that can be
     * performed on the specified model. Each action is typically represented
     * as an instance of the Action class and may include properties such as
     * the action's label, URL, icon, etc.
     *
     * @param Model $model The model instance for which actions are to be retrieved.
     * @return array An array of Action instances representing the available actions.
     */
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

    /**
     * Checks if the checkbox column is enabled.
     *
     * @return bool
     */
    protected function hasCheckboxColumn(): bool
    {
        return $this->hasColumn('checkbox');
    }

    /**
     * Checks if a column exists by given name.
     *
     * @param string $name The name of the column to check.
     * @return bool True if the column exists, otherwise false.
     */
    protected function hasColumn(string $name): bool
    {
        return $this->getColumn($name) !== null;
    }

    /**
     * Gets a column by name.
     *
     * @param string $name The name of the column to retrieve.
     * @return Column|null The column instance if found, otherwise null.
     */
    protected function getColumn(string $name): ?Column
    {
        return collect($this->getColumns())->filter(
            fn ($column) => $column->name === $name
        )->first();
    }
}
