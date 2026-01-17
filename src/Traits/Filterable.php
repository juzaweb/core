<?php

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property array $filterable
 * @method static static|Builder filter(array $params)
 * @method static static|Builder additionFilter(array $params)
 */
trait Filterable
{
    /**
     * Scope a query to filter the results based on the given parameters.
     *
     * This method filters the results of a query based on the given parameters.
     * The parameters are expected to be an associative array where the key is the field name
     * and the value is the value to filter by.
     * The filterable fields are defined in the model's $filterable property.
     *
     * @param Builder|static $query
     * @param array $params
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $params): Builder
    {
        // Get the filterable fields for the model
        $filters = array_filter(
            $this->getFilterable(),
            function ($column) use ($params) {
                return Arr::has($params, $column['field']) && Arr::get($params, $column['field']) !== null;
            }
        );

        // Loop through the filters and add them to the query
        foreach ($filters as $filter) {
            $field = Str::ucfirst(Str::camel($filter['field']));
            // If the model has a scope{$field}Filterable method, call it
            if (method_exists($this, "scope{$field}Filterable")) {
                $query->{"{$field}Filterable"}($params);
                continue;
            }

            $value = $params[$filter['field']];

            // If the value is an array, use the 'in' operator
            if (is_array($value)) {
                $filter['operator'] = 'in';
            }

            // Switch based on the operator
            switch ($filter['operator']) {
                case 'in':
                    // If the value is a string, explode it into an array
                    if (is_string($value)) {
                        $value = array_filter(explode(',', $value), 'trim');
                    }

                    // If the filter is a relation, use the whereHas method
                    if ($filter['type'] === 'relation') {
                        $query->whereHas(
                            $filter['table'],
                            function ($query) use ($filter, $value) {
                                $query->whereIn($filter['column'], $value);
                            }
                        );
                        break;
                    }

                    // Otherwise, use the whereIn method
                    $query->whereIn("{$filter['table']}.{$filter['column']}", $value);
                    break;
                default:
                    // If the filter is a relation, use the whereHas method
                    if ($filter['type'] === 'relation') {
                        $query->whereHas(
                            $filter['table'],
                            function ($query) use ($filter, $value) {
                                $query->where($filter['column'], $filter['operator'], $value);
                            }
                        );
                        break;
                    }

                    // Otherwise, use the where method
                    $query->where("{$filter['table']}.{$filter['column']}", $filter['operator'], $value);
            }
        }

        // If the model has an additionFilter method, call it
        if (method_exists($this, 'scopeAdditionFilter')) {
            $query->additionFilter($params);
        }

        return $query;
    }

    /**
     * Get the filterable fields for the model.
     *
     * The filterable fields are defined in the model's $filterable property.
     * Each field is an associative array with the following keys:
     * - 'column': the column name in the database
     * - 'table': the table name in the database
     * - 'operator': the operator to use for the filter
     * - 'type': the type of the filter (either 'table' or 'relation')
     * - 'field': the field name in the request
     *
     * @return array
     */
    public function getFilterable(): array
    {
        /**
         * Get the table name for the model.
         *
         * @return string
         */
        $tlb = $this->getTable();

        /**
         * Map the filterable fields to their final form.
         *
         * @param mixed $value
         * @param string $key
         * @return array
         */
        return array_values(
            array_map(
                function ($value, $key) use ($tlb) {
                    $column = $key;
                    $operator = $value;
                    $field = $column;

                    /**
                     * If the key is an integer, then the value is the column name.
                     */
                    if (is_int($key)) {
                        $column = $value;
                        $operator = '=';
                        $field = $column;
                    }

                    /**
                     * If the value is an array, then it contains additional information
                     * about the filter.
                     */
                    if (is_array($value)) {
                        $field = $value['field'] ?? $key;
                        $column = $value['column'] ?? $column;
                        $operator = $value['operator'] ?? '=';
                    }

                    /**
                     * If the column name contains a dot, then it's a relation.
                     */
                    $split = explode('.', $column);
                    if (count($split) > 1) {
                        return [
                            'column' => $split[1],
                            'table' => $split[0],
                            'operator' => $operator,
                            'type' => 'relation',
                            'field' => Str::replace(['.'], '_', Str::snake($field)),
                        ];
                    }

                    return [
                        'column' => $column,
                        'table' => $tlb,
                        'operator' => $operator,
                        'type' => 'table',
                        'field' => $field,
                    ];
                },
                $this->filterable ?? [],
                array_keys($this->filterable ?? [])
            )
        );
    }
}
