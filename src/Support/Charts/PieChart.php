<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support\Charts;

use Illuminate\Contracts\Support\Htmlable;

abstract class PieChart
{
    public static function make(...$parameters): static
    {
        return new static(...$parameters);
    }

    public function render(): Htmlable
    {
        return view(
            'core::components.dashboard.pie-chart',
            ['chart' => $this]
        );
    }
}
