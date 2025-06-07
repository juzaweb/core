<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Illuminate\Database\Eloquent\Model;

class FieldFactory
{
    public function text(string|Model $label, string $name, array $options = []): Fields\Text
    {
        return new Fields\Text($label, $name, $options);
    }

    public function textarea(string|Model $label, string $name, array $options = []): Fields\Textarea
    {
        return new Fields\Textarea($label, $name, $options);
    }

    public function select(string|Model $label, string $name, array $options = []): Fields\Select
    {
        return new Fields\Select($label, $name, $options);
    }

    public function image(string|Model $label, string $name, array $options = []): Fields\Image
    {
        return new Fields\Image($label, $name, $options);
    }

    public function images(string|Model $label, string $name, array $options = []): Fields\Images
    {
        return new Fields\Images($label, $name, $options);
    }

    public function checkbox(string|Model $label, string $name, array $options = []): Fields\Checkbox
    {
        return new Fields\Checkbox($label, $name, $options);
    }

    public function editor(string|Model $label, string $name, array $options = []): Fields\Editor
    {
        return new Fields\Editor($label, $name, $options);
    }

    public function password(string|Model $label, string $name, array $options = []): Fields\Text
    {
        $options['type'] = 'password';

        return new Fields\Text($label, $name, $options);
    }
}
