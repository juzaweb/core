<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Fields;

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
