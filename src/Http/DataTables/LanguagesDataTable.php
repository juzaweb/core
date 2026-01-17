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
use Juzaweb\Modules\Core\Models\Language;
use Yajra\DataTables\EloquentDataTable;

class LanguagesDataTable extends DataTable
{
    protected string $actionUrl = 'languages/bulk';

    protected string $rowId = 'code';

    protected array $rawColumns = ['actions', 'checkbox', 'is_default'];

    protected string $websiteId;

    protected int|array $orderBy = [1, 'asc'];

    protected ?string $defaultLanguage = null;

    public function withWebsiteId(string $websiteId): static
    {
        $this->websiteId = $websiteId;

        return $this;
    }

    protected function getDefaultLanguage(): string
    {
        if ($this->defaultLanguage === null) {
            $this->defaultLanguage = Language::default();
        }

        return $this->defaultLanguage;
    }

    public function query(Language $model): QueryBuilder
    {
        return $model->newQuery()->filter($this->request()->all());
    }

    public function getColumns(): array
    {
        return [
            Column::checkbox(),
            Column::id(),
            Column::actions(),
            Column::make('code')->width('100px'),
            Column::make('name'),
            Column::make('is_default')
                ->title(__('core::translation.default'))
                ->width('80px')
                ->center(),
            Column::createdAt(),
        ];
    }

    public function renderColumns(EloquentDataTable $builder): EloquentDataTable
    {
        $defaultLanguage = $this->getDefaultLanguage();

        $builder->editColumn('is_default', function ($row) use ($defaultLanguage) {
            return $row->code === $defaultLanguage
                ? '<i class="fas fa-check text-success"></i>'
                : '';
        });

        return parent::renderColumns($builder);
    }

    public function actions(Model $model): array
    {
        $defaultLanguage = $this->getDefaultLanguage();
        $actions = [
            Action::link(
                __('core::translation.phrases'),
                route('admin.languages.translations', [$this->websiteId, $model->code]),
                'fas fa-language'
            ),
        ];

        if ($model->code !== $defaultLanguage) {
            $actions[] = Action::make(
                __('core::translation.set_as_default'),
                'javascript:void(0)',
                'fas fa-star'
            )
                ->type('action')
                ->action('set-default')
                ->color('warning');
        }

        $actions[] = Action::delete()->disabled($model->code == config('app.fallback_locale') || $model->code == $defaultLanguage);

        return $actions;
    }

    public function bulkActions(): array
    {
        return [
            BulkAction::delete(),
        ];
    }
}
