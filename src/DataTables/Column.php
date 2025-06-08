<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\DataTables;

use Yajra\DataTables\Html\Column as BaseColumn;

class Column extends BaseColumn
{
    public static function actions(): static
    {
        /** @var Column $column */
        $column = static::computed('actions');
        $column->title(__('Actions'));
        $column->addClass('text-center');
        $column->width('200px');
        return $column;
    }

    public static function checkbox($title = ''): static
    {
        return static::make('checkbox')
            ->width('30px')
            ->title('<input type="checkbox" id="select-all">')
            ->className('select-checkbox text-center')
            ->orderable(false)
            ->exportable(false)
            ->searchable(false);
    }

    public static function id(string $id = null, bool $visible = false): static
    {
        $column = static::make('id', $id ?: 'id');
        $column->visible($visible);
        $column->title(__('ID'));
        $column->addClass('text-center');
        return $column;
    }

    public static function rowIndex(): static
    {
        $column = static::make('DT_RowIndex');
        $column->title(__('Row Index'));
        $column->addClass('text-center');
        $column->orderable(false);
        $column->searchable(false);
        return $column;
    }

    public static function createdAt(): static
    {
        /** @var Column */
        return static::computed('created_at')
            ->title(__('Created At'))
            ->addClass('text-center')
            ->searchable(false)
            ->width('150px');
    }

    public static function updatedAt(): static
    {
        /** @var Column */
        return static::computed('updated_at')
            ->title(__('Updated At'))
            ->addClass('text-center')
            ->searchable(false)
            ->width('150px');
    }

    public function center(): static
    {
        $this->addClass('text-center');

        return $this;
    }
}
