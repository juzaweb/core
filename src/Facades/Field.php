<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Support\Fields\Checkbox;
use Juzaweb\Modules\Core\Support\Fields\Currency;
use Juzaweb\Modules\Core\Support\Fields\Date;
use Juzaweb\Modules\Core\Support\Fields\Editor;
use Juzaweb\Modules\Core\Support\Fields\Image;
use Juzaweb\Modules\Core\Support\Fields\Images;
use Juzaweb\Modules\Core\Support\Fields\Language;
use Juzaweb\Modules\Core\Support\Fields\Security;
use Juzaweb\Modules\Core\Support\Fields\Select;
use Juzaweb\Modules\Core\Support\Fields\Slug;
use Juzaweb\Modules\Core\Support\Fields\Tags;
use Juzaweb\Modules\Core\Support\Fields\Text;
use Juzaweb\Modules\Core\Support\Fields\Textarea;
use Juzaweb\Modules\Core\Support\Fields\UploadUrl;

/**
 * @method static Text text(string|Model $label, string $name, array $options = [])
 * @method static Textarea textarea(string|Model $label, string $name, array $options = [])
 * @method static Checkbox checkbox(string|Model $label, string $name, array $options = [])
 * @method static Select select(string|Model $label, string $name, array $options = [])
 * @method static Editor editor(string|Model $label, string $name, array $options = [])
 * @method static Language language(string|Model $label, string $name, array $options = [])
 * @method static UploadUrl uploadUrl(string|Model $label, string $name, array $options = [])
 * @method static Tags tags(string|Model $label, string $name, array $options = [])
 * @method static Image image(string|Model $label, string $name, array $options = [])
 * @method static Images images(string|Model $label, string $name, array $options = [])
 * @method static Date date(string|Model $label, string $name, array $options = [])
 * @method static Slug slug(string|Model $label, string $name, array $options = [])
 * @method static Security security(string|Model $label, string $name, array $options = [])
 * @method static Currency currency(string|Model $label, string $name, array $options = [])
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
