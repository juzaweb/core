<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Fields;

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
