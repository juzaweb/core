<?php

namespace Juzaweb\Modules\Core\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        protected ?string $title = null,
        protected ?string $icon = null,
        protected ?string $description = null,
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('core::components.card', [
                'title' => $this->title,
                'icon' => $this->icon,
                'description' => $this->description,
            ]
        );
    }
}
