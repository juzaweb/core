<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Fields;

class Image extends Field
{
    public function render(): \Illuminate\Contracts\View\View|string
    {
        return view(
            'core::fields.image',
            $this->renderParams()
        );
    }
}
