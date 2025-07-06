<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Http\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Core\DataTables\Action;
use Juzaweb\Core\DataTables\BulkAction;
use Juzaweb\Core\DataTables\Column;
use Juzaweb\Core\DataTables\DataTable;
use Juzaweb\Core\Models\Language;

class LanguagesDataTable extends DataTable
{
    protected string $actionUrl = 'languages/bulk';

    protected string $rowId = 'code';

    protected int|array $orderBy = 3;

    public function query(Language $model): QueryBuilder
    {
        return $model->newQuery()->filter($this->request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::make('code'),
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
            Action::delete(),
        ];
    }
}
