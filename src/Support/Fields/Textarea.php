<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support\Fields;

class Textarea extends Field
{
    public function render(): \Illuminate\Contracts\View\View|string
    {
        return view('core::fields.textarea', [
            'label' => $this->label,
            'name' => $this->name,
            'options' => $this->options,
        ]);
    }
}
