<?php

namespace Juzaweb\Core\Support\Queries;

use Illuminate\Database\Query\Builder as BaseBuilder;
use Illuminate\Support\Arr;
use Juzaweb\Core\Traits\QueryCacheModule;

class Builder extends BaseBuilder implements QueryCacheModuleInterface
{
    use QueryCacheModule;

    /**
     * {@inheritdoc}
     */
    public function get($columns = ['*'])
    {
        return $this->shouldAvoidCache()
            ? parent::get($columns)
            : $this->getFromQueryCache('get', Arr::wrap($columns));
    }

    /**
     * {@inheritdoc}
     */
    public function useWritePdo()
    {
        // Do not cache when using to write pdo for query.
        $this->dontCache();

        // Call parent method
        parent::useWritePdo();

        return $this;
    }

    /**
     * Add a subselect expression to the query.
     *
     * @param  \Closure|$this|string  $query
     * @param  string  $as
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function selectSub($query, $as)
    {
        if (! is_string($query) && get_class($query) == self::class) {
            $this->appendCacheTags($query->getCacheTags() ?? []);
        }

        return parent::selectSub($query, $as);
    }

    /** For Compoships */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        //Here we implement custom support for multi-column 'IN'
        //A multi-column 'IN' is a series of OR/AND clauses
        //TODO: Optimization
        if (is_array($column)) {
            $this->where(
                function ($query) use ($column, $values) {
                    foreach ($values as $value) {
                        $query->orWhere(
                            function ($query) use ($column, $value) {
                                foreach ($column as $index => $aColumn) {
                                    $query->where($aColumn, $value[$index]);
                                }
                            }
                        );
                    }
                }
            );

            return $this;
        }

        return parent::whereIn($column, $values, $boolean, $not);
    }

    public function whereColumn($first, $operator = null, $second = null, $boolean = 'and')
    {
        // If the column and values are arrays, we will assume it is a multi-columns relationship,
        // and we adjust the 'where' clauses accordingly
        if (is_array($first) && is_array($second)) {
            $type = 'Column';

            foreach ($first as $index => $f) {
                $this->wheres[] = [
                    'type'     => $type,
                    'first'    => $f,
                    'operator' => $operator,
                    'second'   => $second[$index],
                    'boolean'  => $boolean,
                ];
            }

            return $this;
        }

        return parent::whereColumn($first, $operator, $second, $boolean);
    }
    /** End For Compoships */
}
