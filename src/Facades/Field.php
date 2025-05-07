<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Core\PageBuilder\Elements\Forms\{Checkbox, Editor, Language, Select, Text, Textarea};
use Juzaweb\Core\PageBuilder\FieldFactory;

/**
 * @method static Text text(string|array|null $name = null, ?string $label = null, array $attributes = [])
 * @method static Textarea textarea(string|array|null $name = null, ?string $label = null, array $attributes = [])
 * @method static Checkbox checkbox(string|array|null $name = null, ?string $label = null, array $attributes = [])
 * @method static Select select(string|array|null $name = null, ?string $label = null, array $attributes = [])
 * @method static Editor editor(string|array|null $name = null, ?string $label = null, array $attributes = [])
 * @method static Language language(string|array|null $name = null, ?string $label = null, array $attributes = [])
 * @see FieldFactory
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
