<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Fields;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class Select extends Field
{
    public function dropDownList(array|Collection $options, ?string $key = null, ?string $value = null): static
    {
        if ($key) {
            if (is_array($options)) {
                $options = collect($options)->pluck($value, $key);
            } elseif ($options instanceof Collection) {
                $options = $options->pluck($value, $key);
            }
        }

        $this->options['options'] = $options;

        return $this;
    }

    public function value(string|int|array|null $value): static
    {
        $this->options['value'] = $value;

        return $this;
    }

    public function selected(string|int|array|null $value): static
    {
        $this->options['value'] = $value;

        return $this;
    }

    public function autocomplete(bool $autocomplete = true): static
    {
        $this->options['autocomplete'] = $autocomplete;

        return $this;
    }

    public function multiple(bool $multiple = true): static
    {
        $this->options['multiple'] = $multiple;

        return $this;
    }

    public function dataUrl(string $url): static
    {
        $this->options['data_url'] = $url;

        return $this;
    }

    public function loadDataModel(string $model, string $field = 'name'): static
    {
        $this->options['data_url'] = load_data_url($model, $field);

        return $this;
    }

    public function render(): View|string
    {
        return view('core::fields.select', $this->renderParams());
    }
}
