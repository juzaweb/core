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

class Text extends Field
{
    public function slugSource()
    {
        $this->options['classes'] = array_merge(
            $this->options['classes'] ?? [],
            ['slug-source']
        );

        return $this;
    }

    public function render(): View|string
    {
        return view(
            'core::fields.text',
            $this->renderParams()
        );
    }
}
