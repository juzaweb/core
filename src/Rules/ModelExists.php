<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Core\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class ModelExists implements Rule
{
    /**
     * @var string
     */
    private string $modelClass;

    /**
     * @var string
     */
    private string $modelAttribute;

    /**
     * @var callable
     */
    private $closure;

    /**
     * @var string
     */
    private string $attribute;

    /**
     * @var mixed
     */
    private mixed $value;

    public function __construct(string $modelClass, string $modelAttribute = 'id', callable $closure = null)
    {
        $this->modelClass = $modelClass;
        $this->modelAttribute = $modelAttribute;
        $this->closure = $closure ?? function () {
        };
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;
        $this->value = $value;

        return $this->modelClass::query()
            ->when(
                is_array($value),
                function (Builder $query) {
                    $query->whereIn($this->modelAttribute, $this->value);
                },
                function (Builder $query) {
                    $query->where($this->modelAttribute, $this->value);
                }
            )
            ->tap($this->closure)
            ->exists();
    }

    public function message(): string
    {
        return trans(
            'validation.model_exists',
            [
                'attribute' => $this->attribute,
                'value' => $this->value,
                'model' => class_basename($this->modelClass),
                'model_attribute' => $this->modelAttribute,
            ]
        );
    }
}
