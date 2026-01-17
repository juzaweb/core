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

class ThemeInit extends Component
{
    public function render(): View|string
    {
        return view('admin::components.theme-init');
    }
}
