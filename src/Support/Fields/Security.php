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

class Security extends Field
{
    public function render(): View|string
    {
        $this->options['disabled'] = isset($this->options['value']) && $this->options['value'] != '';

        return view(
            'admin::fields.security',
            $this->renderParams()
        );
    }
}
