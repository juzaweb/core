<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Support\Fields;

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

    public function selected(string|int|null $value): static
    {
        $this->options['value'] = $value;

        return $this;
    }

    public function autocomplete(bool $autocomplete): static
    {
        $this->options['autocomplete'] = $autocomplete;

        return $this;
    }

    public function render(): View|string
    {
        return view('core::fields.select', $this->renderParams());
    }
}
