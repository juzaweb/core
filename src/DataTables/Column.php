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
    /**
     * Creates a column configuration for actions with predefined settings.
     *
     * This static method generates a column specifically for handling actions
     * in a data table. It sets the column title to "Actions", centers the text,
     * and sets a fixed width of 200 pixels.
     *
     * @return static Returns a configured Column instance for actions.
     */
    public static function actions(): static
    {
        /** @var Column $column */
        $column = static::computed('actions');
        $column->title(__('Actions'));
        $column->addClass('text-center');
        $column->width('200px');
        return $column;
    }

    /**
     * Creates a column configuration for checkbox with predefined settings.
     *
     * This static method generates a column specifically for handling checkbox
     * in a data table. It sets the column title to a checkbox with the given
     * title, centers the text, and sets a fixed width of 30 pixels. It also
     * disables ordering, exporting, and searching.
     *
     * @param string $title The title of the column.
     * @return static Returns a configured Column instance for checkbox.
     */
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

    /**
     * Creates a column configuration for displaying the record ID.
     *
     * This static method generates a column specifically for displaying the
     * record ID in a data table. It sets the column title to "ID", centers the
     * text, and sets the initial visibility to the given value. It also sets
     * the default field name to "id".
     *
     * @param string $id The field name to use for the record ID.
     * @param bool $visible The initial visibility of the column.
     * @return static Returns a configured Column instance for the record ID.
     */
    public static function id(?string $id = null, bool $visible = false): static
    {
        $column = static::make('id', $id ?? 'id');
        $column->visible($visible);
        $column->title(__('ID'));
        $column->addClass('text-center');
        return $column;
    }

    /**
     * Create a column for the row index.
     * This column is not orderable or searchable.
     *
     * @return static
     */
    public static function rowIndex(): static
    {
        $column = static::make('DT_RowIndex');
        $column->title(__('Row Index'));
        $column->addClass('text-center');
        $column->orderable(false);
        $column->searchable(false);
        return $column;
    }

    /**
     * Create a column for the "created_at" timestamp.
     * This column is not searchable and is displayed with a width of 150px.
     *
     * @return static
     */
    public static function createdAt(): static
    {
        /** @var Column */
        return static::computed('created_at')
            ->title(__('Created At'))
            ->addClass('text-center')
            ->searchable(false)
            ->width('150px');
    }

    /**
     * Create a column for the "updated_at" timestamp.
     * This column is not searchable and is displayed with a width of 150px.
     *
     * @return static
     */
    public static function updatedAt(): static
    {
        /** @var Column */
        return static::computed('updated_at')
            ->title(__('Updated At'))
            ->addClass('text-center')
            ->searchable(false)
            ->width('150px');
    }

    /**
     * Adds the 'text-center' class to the column, centering its content.
     *
     * @return static The current instance for method chaining.
     */
    public function center(): static
    {
        $this->addClass('text-center');

        return $this;
    }
}
