<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\DataTables;

use Illuminate\Support\HtmlString;
use Yajra\DataTables\Html\Builder;

class HtmlBuilder extends Builder
{
    protected array $bulkActions = [];

    protected ?string $actionUrl = null;

    public function table(array $attributes = [], bool $drawFooter = false, bool $drawSearch = false): HtmlString
    {
        $table = parent::table($attributes, $drawFooter, $drawSearch);

        $filters = $this->view->make(
            'core::components.datatables.filters',
            [
                'bulkActions' => $this->bulkActions,
                'tableId' => $this->getTableId(),
                'searchable' => $this->attributes['searching'] ?? true,
                'endpoint' => $this->actionUrl,
            ]
        )->render();

        return new HtmlString($filters . $table->toHtml());
    }

    public function actionUrl(string $url): static
    {
        $this->actionUrl = $url;

        return $this;
    }

    public function bulkActions(array $actions): static
    {
        $this->bulkActions = $actions;

        return $this;
    }
}
