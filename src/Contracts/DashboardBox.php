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

interface DashboardBox
{
    /**
     * Render the dashboard box.
     */
    public function render(): Htmlable;

    /**
     * Get the title of the dashboard box.
     */
    public function getTitle(): string;

    /**
     * Get the icon of the dashboard box.
     */
    public function getIcon(): string;

    /**
     * Get the data for the dashboard box.
     */
    public function getData(): int;

    /**
     * Get the background class for the dashboard box.
     */
    public function getBackground(): string;
}
