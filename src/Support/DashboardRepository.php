<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support;

use Juzaweb\Core\Contracts\Dashboard;
use Juzaweb\Core\Contracts\DashboardBox;
use Juzaweb\Core\Contracts\LineChart;

class DashboardRepository implements Dashboard
{
    protected array $boxes = [];

    protected array $charts = [];

    public function box(string $name, DashboardBox $box): void
    {
        $this->boxes[$name] = $box;
    }

    public function chart(string $name, LineChart $box): void
    {
        $this->charts[$name] = $box;
    }

    /**
     * Get all dashboard boxes.
     *
     * @return array<string, DashboardBox>
     */
    public function boxes(): array
    {
        return $this->boxes;
    }

    /**
     * Get all dashboard charts.
     *
     * @return array<string, DashboardBox>
     */
    public function charts(): array
    {
        return $this->charts;
    }
}
