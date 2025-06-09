<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Fields;

class Editor extends Field
{
    public function render(): \Illuminate\Contracts\View\View|string
    {
        return view(
            'core::fields.editor',
            $this->renderParams()
        );
    }
}
