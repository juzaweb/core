<?php

namespace Juzaweb\Modules\Core\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Juzaweb\Modules\Core\Models\Model;

class SeoMeta extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        protected Model $model,
        protected ?string $locale = null,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(
            'core::components.seo-meta',
            [
                'model' => $this->model,
                'locale' => $this->locale ?? config('translatable.fallback_locale'),
            ]
        );
    }
}
