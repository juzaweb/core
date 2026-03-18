<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Contracts\Support\Htmlable;

interface LineChart
{
    /**
     * Get the title of the dashboard chart.
     */
    public function getTitle(): string;

    /**
     * Get the icon of the dashboard chart.
     */
    public function getIcon(): string;

    /**
     * Get the data for the chart.
     */
    public function getData(): array;

    /**
     * Render the chart as HTML.
     */
    public function render(): Htmlable;
}
