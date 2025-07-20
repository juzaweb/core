<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Contracts;

use Illuminate\Contracts\Support\Htmlable;

interface LineChart
{
    /**
     * Get the title of the dashboard chart.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get the icon of the dashboard chart.
     *
     * @return string
     */
    public function getIcon(): string;

    /**
     * Get the labels for the chart.
     *
     * @return array
     */
    public function getLabels(): array;

    /**
     * Get the data for the chart.
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Render the chart as HTML.
     *
     * @return Htmlable
     */
    public function render(): Htmlable;
}
