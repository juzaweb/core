<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Juzaweb\Core\DataTables\Action;
use Juzaweb\Core\DataTables\BulkAction;
use Juzaweb\Core\DataTables\Column;
use Juzaweb\Core\DataTables\ColumnEditer;
use Juzaweb\Core\DataTables\DataTable;
use Yajra\DataTables\EloquentDataTable;

class UsersDataTable extends DataTable
{
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::make('name'),
            Column::make('email'),
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
}
