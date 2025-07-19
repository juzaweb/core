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

interface DashboardBox
{
    /**
     * Render the dashboard box.
     *
     * @return Htmlable
     */
    public function render(): Htmlable;

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

    /**
     * Get the background class for the dashboard box.
     *
     * @return string
     */
    public function getBackground(): string;
}
