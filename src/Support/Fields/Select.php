<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Fields;

class Select extends Field
{
    public function dropDownList(array $options): static
    {
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

    public function render(): \Illuminate\Contracts\View\View|string
    {
        return view('core::fields.select', [
            'label' => $this->label,
            'name' => $this->name,
            'options' => $this->options,
        ]);
    }
}
