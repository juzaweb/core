<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Fields;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Model;

abstract class Field implements Renderable, \Stringable, Htmlable
{
    public function __construct(
        protected string|Model $label,
        protected string $name,
        protected array $options = []
    ) {
    }

    public function id(string $id): static
    {
        $this->options['id'] = $id;

        return $this;
    }

    public function placeholder(string $placeholder): static
    {
        $this->options['placeholder'] = $placeholder;

        return $this;
    }

    public function value(string $value): static
    {
        $this->options['value'] = $value;

        return $this;
    }

    public function classes(string|array $classes): static
    {
        if (! is_array($classes)) {
            $classes = [$classes];
        }

        $this->options['classes'] = $classes;

        return $this;
    }

    abstract public function render(): \Illuminate\Contracts\View\View|string;

    public function __toString(): string
    {
        return $this->render();
    }

    public function toHtml(): \Illuminate\Contracts\View\View|string
    {
        return $this->render();
    }

    protected function renderParams(array $extra = []): array
    {
        $params = [
            'label' => $this->label,
            'name' => $this->name,
            'options' => $this->options,
        ];

        if ($this->label instanceof Model) {
            $params['label'] = title_from_key($this->name);
            $params['value'] = $this->label->{$this->name};
        }

        return array_merge($params, $extra);
    }
}
