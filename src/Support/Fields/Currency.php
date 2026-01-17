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

class Currency extends Field
{
    public function symbol(string $symbol): static
    {
        $this->options['symbol'] = $symbol;

        return $this;
    }

    public function decimals(int $decimals): static
    {
        $this->options['decimals'] = $decimals;

        return $this;
    }

    public function separator(string $thousand = ',', string $decimal = '.'): static
    {
        $this->options['thousand_separator'] = $thousand;
        $this->options['decimal_separator'] = $decimal;

        return $this;
    }

    public function symbolPosition(string $position = 'left'): static
    {
        $this->options['symbol_position'] = $position;

        return $this;
    }

    public function render(): View|string
    {
        return view('core::fields.currency', $this->renderParams());
    }
}
