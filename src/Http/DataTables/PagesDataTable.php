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
use Juzaweb\Core\Models\Page;

class PagesDataTable extends DataTable
{
    protected string $actionUrl = 'pages/bulk';

    public function query(Page $model): QueryBuilder
    {
        return $model->newQuery()
            ->withTranslation()
            ->filter($this->request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::editLink(
                'title',
                admin_url("pages/{id}/edit"),
                'Title'
            ),
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
            Action::edit(admin_url("pages/{$model->id}/edit")),
            Action::delete(),
        ];
    }
}
