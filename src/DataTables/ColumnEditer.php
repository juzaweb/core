<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\DataTables;

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
        $actions = array_filter($actions, fn ($action) => $action->isVisible());

        return view(
            'admin::components.datatables.actions',
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
            'admin::components.datatables.edit-link',
            compact('model', 'editUrl', 'data')
        );
    }

    /**
     * Create a column of checkboxes.
     *
     * @param  Model  $model
     * @param  string  $rowId
     * @return View
     */
    public static function checkbox(Model $model, string $rowId = 'id'): View
    {
        return view(
            'admin::components.datatables.checkbox',
            ['model' => $model, 'rowId' => $rowId]
        );
    }
}
