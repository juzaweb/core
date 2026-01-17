<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\Contracts\Field;
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

class FieldFactory implements Field
{
    public function text(string|Model $label, string $name, array $options = []): Text
    {
        return new Text($label, $name, $options);
    }

    public function textarea(string|Model $label, string $name, array $options = []): Textarea
    {
        return new Textarea($label, $name, $options);
    }

    public function select(string|Model $label, string $name, array $options = []): Select
    {
        return new Select($label, $name, $options);
    }

    public function slug(string|Model $label, string $name, array $options = []): Slug
    {
        return new Slug($label, $name, $options);
    }

    public function image(string|Model $label, string $name, array $options = []): Image
    {
        return new Image($label, $name, $options);
    }

    public function images(string|Model $label, string $name, array $options = []): Images
    {
        return new Images($label, $name, $options);
    }

    public function checkbox(string|Model $label, string $name, array $options = []): Checkbox
    {
        return new Checkbox($label, $name, $options);
    }

    public function editor(string|Model $label, string $name, array $options = []): Editor
    {
        return new Editor($label, $name, $options);
    }

    public function password(string|Model $label, string $name, array $options = []): Text
    {
        $options['type'] = 'password';

        return new Text($label, $name, $options);
    }

    public function uploadUrl(string|Model $label, string $name, array $options = []): UploadUrl
    {
        return new UploadUrl($label, $name, $options);
    }

    public function language(string|Model|null $label, ?string $name, array $options = []): Language
    {
        return new Language($label, $name, $options);
    }

    public function tags(string|Model $label, string $name, array $options = []): Tags
    {
        return new Tags($label, $name, $options);
    }

    public function number(string|Model $label, string $name, array $options = []): Text
    {
        $options['type'] = 'number';

        return new Text($label, $name, $options);
    }

    public function date(string|Model $label, string $name, array $options = []): Date
    {
        return new Date($label, $name, $options);
    }

    public function security(string|Model $label, string $name, array $options = [])
    {
        return new Security($label, $name, $options);
    }

    public function currency(string|Model $label, string $name, array $options = []): Currency
    {
        return new Currency($label, $name, $options);
    }
}
