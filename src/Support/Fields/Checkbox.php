<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Fields;

use Illuminate\Contracts\View\View;

class Checkbox extends Field
{
    public function render(): View|string
    {
        return view(
            'core::fields.checkbox',
            $this->renderParams()
        );
    }
}
