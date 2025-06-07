<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
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

    public function selected(string|int $value): static
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
