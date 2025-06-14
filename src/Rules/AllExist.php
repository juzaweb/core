<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Rules;

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
            return $fail(__('The value of :attribute must be an array.', ['attribute' => $attribute]));
        }

        if (empty($this->table) || empty($this->column)) {
            return $fail(__('The table and column must be set for.'));
        }

        if (empty($value)) {
            return $fail(__('The value of :attribute must not be empty.', ['attribute' => $attribute]));
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

                return $fail(__(
                    "The value :value of :attribute does not exist in :table table under :column column.",
                    ['attribute' => $attribute, 'table' => $this->table, 'column' => $this->column, 'value' => $v])
                );
            }
        } catch (\Exception $e) {
            return $fail(__('Validation failed: :message', ['message' => $e->getMessage()]));
        }
    }
}
