<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Rules;

use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\DB;

class AllExist implements InvokableRule
{
    public static function make(string $table, string $column): static
    {
        return new static($table, $column);
    }

    public function __construct(private string $table, private string $column)
    {
        //
    }

    public function __invoke($attribute, $value, $fail)
    {
        if (! is_array($value)) {
            return $fail(__('admin::translation.the_value_of_attribute_must_be_an_array', ['attribute' => $attribute]));
        }

        if (empty($this->table) || empty($this->column)) {
            return $fail(__('admin::translation.the_table_and_column_must_be_set_for'));
        }

        if (empty($value)) {
            return $fail(__('admin::translation.the_value_of_attribute_must_not_be_empty', ['attribute' => $attribute]));
        }

        try {
            $value = array_unique($value);
            $dbIds = DB::table($this->table)
                ->selectRaw("distinct {$this->column} id")
                ->whereIn($this->column, $value)
                ->pluck('id');

            foreach ($value as $v) {
                if ($dbIds->contains($v)) {
                    continue;
                }

                return $fail(__("admin::translation.the_value_value_of_attribute_does_not_exist_in_table_table_under_column_column",
                    ['attribute' => $attribute, 'table' => $this->table, 'column' => $this->column, 'value' => $v])
                );
            }
        } catch (\Exception $e) {
            return $fail(__('admin::translation.validation_failed_message', ['message' => $e->getMessage()]));
        }
    }
}
