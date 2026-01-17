<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Http\DataTables;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Admin\Models\Users\Member;
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;

class MembersDataTable extends DataTable
{
    protected string $actionUrl = 'members/bulk';

    public function query(Member $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::editLink('name', admin_url('members/{id}/edit'), __('core::translation.name')),
            Column::make('email'),
            Column::createdAt(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('members.delete'),
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("members/{$model->id}/edit"))
                ->can('members.edit'),
            Action::delete()
                ->disabled($model->user_id !== null)
                ->can('members.delete'),
        ];
    }
}
