<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Support\Fields;

use Illuminate\Contracts\View\View;

class Images extends Field
{
    public function render(): View|string
    {
        return view(
            'core::fields.images',
            $this->renderParams()
        );
    }
}
