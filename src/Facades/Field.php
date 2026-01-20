<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Juzaweb\Modules\Core\Support\Fields\Text text(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Textarea textarea(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Select select(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Slug slug(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Image image(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Images images(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Checkbox checkbox(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Editor editor(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Text password(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\UploadUrl uploadUrl(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Language language(string|Model|null $label, ?string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Tags tags(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Text number(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Date date(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Security security(string|Model $label, string $name, array $options = [])
 * @method static \Juzaweb\Modules\Core\Support\Fields\Currency currency(string|Model $label, string $name, array $options = [])
 * @see \Juzaweb\Modules\Core\Support\FieldFactory
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
        return \Juzaweb\Modules\Core\Contracts\Field::class;
    }
}
