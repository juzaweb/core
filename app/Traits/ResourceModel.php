<?php
/**
 * MYMO CMS - Free Laravel CMS
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 6/9/2021
 * Time: 10:37 PM
 */

namespace Juzaweb\Core\Traits;

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
            $builder->where(function ($q) use ($params) {
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