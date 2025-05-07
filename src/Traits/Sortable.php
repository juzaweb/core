<?php

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

/**
 * @property array $sortable
 * @property array $sortDefault
 * @method static static|Builder sort(array $params)
 * @method static static|Builder additionSort(array $params)
 */
trait Sortable
{
    /**
     * Scope a query to sort the results.
     *
     * This method can be used to sort the results of a query.
     * The sorting is done using the columns specified in the $sortable property of the model.
     * By default, the sorting is done in descending order.
     * The order can be changed by passing the 'sort_order' parameter in the $params array.
     *
     * @param  Builder|static  $query
     * @param  array  $params
     * @return Builder
     */
    public function scopeSort(Builder $query, array $params): Builder
    {
        $tlb = $this->getTable();
        $sorts = $this->getSortable();
        $sortBys = $params['sort_by'] ?? null;
        $sortOrders = $params['sort_order'] ?? 'DESC';

        // If no sorting columns were specified, use the default sorting
        if (empty($sortBys) && ($defaultSorts = $this->getSortableDefault())) {
            foreach ($defaultSorts as $column => $sort) {
                $query->orderBy("{$tlb}.{$column}", $sort);
            }
        }

        // If the sort_by parameter is not an array, make it an array
        if (!is_array($sortBys)) {
            $sortBys = [$sortBys];
        }

        // If the sort_order parameter is not an array, make it an array
        if (!is_array($sortOrders)) {
            $sortOrders = [$sortOrders];
        }

        // Loop through the columns to sort
        foreach ($sortBys as $index => $sortBy) {
            // If the column is not in the $sortable array, skip it
            if (!in_array($sortBy, $sorts)) {
                continue;
            }

            // Get the sort order from the $sortOrders array
            $sortOrder = $sortOrders[$index] ?? 'DESC';

            // If the sort order is not valid, set it to 'DESC'
            if (!in_array(Str::upper($sortOrder), ['ASC', 'DESC'])) {
                $sortOrder = 'DESC';
            }

            // Add the sorting to the query
            $query->orderBy("{$tlb}.{$sortBy}", $sortOrder);
        }

        // If the additionSort method exists, call it
        if (method_exists($this, 'scopeAdditionSort')) {
            $query->additionSort($params);
        }

        return $query;
    }

    /**
     * Get the sortable fields for the model.
     *
     * This method returns an array of sortable fields for the model.
     * If the `$sortable` property is not set, an empty array is returned.
     *
     * @return array The sortable fields for the model.
     */
    public function getSortable(): array
    {
        return $this->sortable ?? [];
    }

    /**
     * Get the default sortable fields for the model.
     *
     * This method returns an array of default sortable fields for the model.
     * If the `$sortDefault` property is not set, an array with the `created_at`
     * field sorted in descending order is returned.
     *
     * @return array The default sortable fields for the model.
     */
    public function getSortableDefault(): array
    {
        return $this->sortDefault ?? ['created_at' => 'DESC'];
    }
}
