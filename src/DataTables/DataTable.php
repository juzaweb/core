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

use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Services\DataTable as BaseDataTable;

abstract class DataTable extends BaseDataTable
{
    protected string $dom = '<"table-responsive"rt><"row"<"col-md-5"l><"col-md-7"p>><"clear">';

    protected array $rawColumns = ['actions', 'checkbox'];

    protected string $id = 'jw-datatable';

    abstract public function getColumns(): array;

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->dom($this->dom)
            ->setTableId($this->id)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->selectStyleSingle();
    }
}
