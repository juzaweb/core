<?php

namespace Juzaweb\Modules\Core\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class LanguageCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        protected string|Model|null $label,
        protected ?string $name,
        protected array $options = [],
        protected ?string $locale = null
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(
            'admin::components.language-card',
            [
                'label' => $this->label,
                'name' => $this->name,
                'options' => $this->options,
                'locale' => $this->locale ?? config('translatable.fallback_locale'),
            ]
        );
    }
}
