<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
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

    public static function editLink(Model $model, string $editUrl, string $data = 'name'): View
    {
        return view(
            'core::components.datatables.edit-link',
            compact('model', 'editUrl', 'data')
        );
    }

    /**
     * Create a column of checkboxes.
     *
     * @param Model $model
     * @return View
     */
    public static function checkbox(Model $model): View
    {
        return view(
            'core::components.datatables.checkbox',
            ['model' => $model]
        );
    }
}
