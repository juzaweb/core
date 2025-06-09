<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
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
