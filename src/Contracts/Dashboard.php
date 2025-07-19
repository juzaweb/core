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

interface Dashboard
{
    /**
     * Add a dashboard box.
     *
     * @param string $name
     * @param DashboardBox $box
     * @return void
     */
    public function box(string $name, DashboardBox $box): void;

    /**
     * Get all dashboard boxes.
     *
     * @return array
     */
    public function boxes(): array;
}
