<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Str;

class Model extends EloquentModel
{
    protected $prefix;
    protected $tableWithPrefix;

    /**
     * Get the prefix associated with the model.
     *
     * @return string
     */
    public function getTbPrefix()
    {
        return $this->prefix ?? '';
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        if (!empty($this->tableWithPrefix)) {
            return $this->tableWithPrefix;
        }

        if ($this instanceof Pivot && ! isset($this->table)) {
            $this->setTable($this->getTbPrefix() . str_replace(
                    '\\',
                    '',
                    Str::snake(Str::singular(class_basename($this)))
                ));

            return $this->tableWithPrefix;
        }

        return $this->getTbPrefix() . Str::snake(Str::pluralStudly(class_basename($this)));
    }

    /**
     * Set the table associated with the model.
     *
     * @param  string  $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->tableWithPrefix = $table;

        return $this;
    }

    public static function getTableName()
    {
        return with(new static())->getTable();
    }


}