<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\DataTables;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ColumnEditer
{
    /**
     * Create a column of actions.
     */
    public static function actions(Model $model, array $actions, string $endpoint): View
    {
        $actions = array_filter($actions, fn ($action) => $action->isVisible());

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
     */
    public static function checkbox(Model $model, string $rowId = 'id'): View
    {
        return view(
            'core::components.datatables.checkbox',
            ['model' => $model, 'rowId' => $rowId]
        );
    }
}
