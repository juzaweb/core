<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\DataTables;

use Illuminate\Database\Eloquent\Model;

class ColumnEditer
{
    public static function actions(Model $model, array $actions)
    {
        return view(
            'core::components.datatables.actions',
            [
                'model' => $model,
                'actions' => $actions,
            ]
        );
    }

    public static function name(Model $model): string
    {
        return view(
            'core::components.datatables.name',
            [
                'model' => $model
            ]
        )->render();
    }

    public static function checkbox(Model $model): string
    {
        return view(
            'core::components.datatables.checkbox',
            ['model' => $model]
        )->render();
    }
}
