<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\Support\Fields\Checkbox;
use Juzaweb\Modules\Core\Support\Fields\Currency;
use Juzaweb\Modules\Core\Support\Fields\Date;
use Juzaweb\Modules\Core\Support\Fields\Editor;
use Juzaweb\Modules\Core\Support\Fields\Image;
use Juzaweb\Modules\Core\Support\Fields\Images;
use Juzaweb\Modules\Core\Support\Fields\Language;
use Juzaweb\Modules\Core\Support\Fields\Select;
use Juzaweb\Modules\Core\Support\Fields\Slug;
use Juzaweb\Modules\Core\Support\Fields\Tags;
use Juzaweb\Modules\Core\Support\Fields\Text;
use Juzaweb\Modules\Core\Support\Fields\Textarea;
use Juzaweb\Modules\Core\Support\Fields\UploadUrl;

/**
 * @see \Juzaweb\Modules\Core\Support\FieldFactory
 */
interface Field
{
    public function text(string|Model $label, string $name, array $options = []): Text;

    public function textarea(string|Model $label, string $name, array $options = []): Textarea;

    public function select(string|Model $label, string $name, array $options = []): Select;

    public function slug(string|Model $label, string $name, array $options = []): Slug;

    public function image(string|Model $label, string $name, array $options = []): Image;

    public function images(string|Model $label, string $name, array $options = []): Images;

    public function checkbox(string|Model $label, string $name, array $options = []): Checkbox;

    public function editor(string|Model $label, string $name, array $options = []): Editor;

    public function password(string|Model $label, string $name, array $options = []): Text;

    public function uploadUrl(string|Model $label, string $name, array $options = []): UploadUrl;

    public function language(string|Model|null $label, ?string $name, array $options = []): Language;

    public function tags(string|Model $label, string $name, array $options = []): Tags;

    public function number(string|Model $label, string $name, array $options = []): Text;

    public function date(string|Model $label, string $name, array $options = []): Date;

    public function security(string|Model $label, string $name, array $options = []);

    public function currency(string|Model $label, string $name, array $options = []): Currency;
}
