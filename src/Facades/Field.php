<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Juzaweb\Core\Support\Fields;

/**
 * @method static Fields\Text text(string|Model $label, string $name, array $options = [])
 * @method static Fields\Textarea textarea(string|Model $label, string $name, array $options = [])
 * @method static Fields\Checkbox checkbox(string|Model $label, string $name, array $options = [])
 * @method static Fields\Select select(string|Model $label, string $name, array $options = [])
 * @method static Fields\Editor editor(string|Model $label, string $name, array $options = [])
 * @method static Fields\Language language(string|Model $label, string $name, array $options = [])
 * @see \Juzaweb\Core\Support\FieldFactory
 */
class Field extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Core\Contracts\Field::class;
    }
}
