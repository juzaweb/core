<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Repeater extends Component
{
    public function __construct(
        protected string $name,
        protected string $view,
        protected array $items = []
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|\Closure|string
    {
        return view('admin::components.repeater', [
                'name' => $this->name,
                'view' => $this->view,
                'items' => $this->items,
            ]
        );
    }
}
