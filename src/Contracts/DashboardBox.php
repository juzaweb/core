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

interface DashboardBox
{
    /**
     * Render the dashboard box.
     *
     * @return \Illuminate\Contracts\Support\Htmlable
     */
    public function render(): \Illuminate\Contracts\Support\Htmlable;

    /**
     * Get the title of the dashboard box.
     *
     * @return string
     */
    public function getTitle(): string;

    /**
     * Get the icon of the dashboard box.
     *
     * @return string
     */
    public function getIcon(): string;

    /**
     * Get the data for the dashboard box.
     *
     * @return int
     */
    public function getData(): int;
}
