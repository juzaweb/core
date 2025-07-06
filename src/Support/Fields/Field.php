<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Support\Fields;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Juzaweb\Core\Support\Traits\HasRules;
use Juzaweb\Core\Traits\Whenable;
use Stringable;

abstract class Field implements Renderable, Stringable, Htmlable
{
    use HasRules, Whenable;

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

    abstract public function render(): View|string;

    public function __toString(): string
    {
        return $this->render();
    }

    public function toHtml(): View|string
    {
        return $this->render();
    }

    protected function renderParams(array $extra = []): array
    {
        $params = [
            'label' => $this->label,
            'name' => $this->name,
            'options' => $this->options,
            'rules' => $this->getRules(),
        ];

        if ($this->label instanceof Model) {
            $params['label'] = title_from_key($this->name);
            $params['options']['value'] = $this->label->{$this->name};
        }

        return array_merge($params, $extra);
    }
}
