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

class Form extends Component
{
    public function __construct(
        protected string $action = '',
        protected string $method = 'POST',
        protected bool $notify = false,
        protected bool $token = false,
    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|\Closure|string
    {
        return view('admin::components.form', [
                'action' => $this->action,
                'method' => $this->method,
                'notify' => $this->notify,
                'token' => $this->token,
            ]
        );
    }
}
