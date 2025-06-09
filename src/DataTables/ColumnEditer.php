<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\DataTables;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ColumnEditer
{
    /**
     * Create a column of actions.
     *
     * @param Model $model
     * @param array $actions
     * @param string $endpoint
     * @return View
     */
    public static function actions(Model $model, array $actions, string $endpoint): View
    {
        return view(
            'core::components.datatables.actions',
            [
                'model' => $model,
                'actions' => $actions,
                'endpoint' => $endpoint,
            ]
        );
    }

    public static function name(Model $model): View
    {
        return view(
            'core::components.datatables.name',
            [
                'model' => $model
            ]
        );
    }

    public static function checkbox(Model $model): View
    {
        return view(
            'core::components.datatables.checkbox',
            ['model' => $model]
        );
    }
}
