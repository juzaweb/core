<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

/**
 * @method \Illuminate\Database\Eloquent\Builder whereFilter(array $params)
 **/
trait ResourceModel
{
    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $params
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereFilter($builder, $params = [])
    {
        if (empty($this->searchAttributes)) {
            return $builder;
        }

        if (Arr::has($params, 'keyword')) {
            $builder->where(function (Builder $q) use ($params) {
                $keyword = trim($params['keyword']);
                foreach ($this->searchAttributes as $attribute) {
                    $q->orWhere($attribute, 'like', '%'. $keyword .'%');
                }
            });
        }

        return $builder;
    }

    public function getFieldName()
    {
        if (!empty($this->fieldName)) {
            return $this->fieldName;
        }

        if (in_array('title', $this->fillable)) {
            return 'title';
        }

        return 'name';
    }
}