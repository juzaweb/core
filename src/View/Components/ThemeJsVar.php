<?php

namespace Juzaweb\Modules\Core\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ThemeJsVar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(protected ?string $viewPage = null)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(
            'core::components.theme-js-var',
            [
                'viewPage' => $this->viewPage,
            ]
        );
    }
}
