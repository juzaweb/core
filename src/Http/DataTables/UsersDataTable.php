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
use Juzaweb\Core\Models\User;

class UsersDataTable extends DataTable
{
    protected string $actionUrl = 'users/bulk';

    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where(['is_super_admin' => false])->filter(request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::editLink('name', admin_url('users/{id}/edit'), __('Name')),
            Column::make('email'),
            Column::createdAt(),
            Column::actions(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete()->can('users.delete'),
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::edit(admin_url("users/{$model->id}/edit"))
                ->can('users.edit'),
            Action::delete()
                ->disabled($model->isSuperAdmin())
                ->can('users.delete'),
        ];
    }
}
