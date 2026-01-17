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
use Juzaweb\Modules\Core\DataTables\Action;
use Juzaweb\Modules\Core\DataTables\BulkAction;
use Juzaweb\Modules\Core\DataTables\Column;
use Juzaweb\Modules\Core\DataTables\DataTable;
use Juzaweb\Modules\Core\Models\Pages\Page;
use Yajra\DataTables\EloquentDataTable;

class PagesDataTable extends DataTable
{
    protected string $actionUrl = 'pages/bulk';

    protected array $rawColumns = ['actions', 'checkbox', 'is_home'];

    public function query(Page $model): QueryBuilder
    {
        return $model->newQuery()
            ->with(['media'])
            ->withTranslation(null)
            ->filter($this->request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::actions(),
            Column::editLink(
                'title',
                admin_url("pages/{id}/edit"),
                'Title'
            ),
            Column::computed('is_home')
                ->title(__('admin::translation.pages'))
                ->width('100px')
                ->addClass('text-center')
                ->orderable(false)
                ->searchable(false),
            Column::createdAt(),
        ];
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete(),
            BulkAction::make(__('admin::translation.translate'), null, 'fas fa-language')
                ->type('url')
                ->action('translate')
                ->can('pages.edit'),
        ];
    }

    public function actions(Model $model): array
    {
        return [
            Action::link(__('admin::translation.view_online'), $model->getUrl(), 'fas fa-eye')
                ->color('info')
                ->target('_blank'),
            Action::edit(admin_url("pages/{$model->id}/edit")),
            Action::delete(),
        ];
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        $builder->editColumn('is_home', function (Page $page) {
            if ($page->id == theme_setting('home_page')) {
                return '<span class="badge badge-success"><i class="fas fa-home"></i> ' . e(__('admin::translation.home')) . '</span>';
            }
            return '<span class="text-muted">-</span>';
        });

        return $builder;
    }
}
