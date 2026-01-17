<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support;

use Juzaweb\Modules\Core\Contracts\Chart;

class ChartRepository implements Chart
{
    protected array $charts = [];

    public function chart(string $key, string $class): void
    {
        $this->charts[$key] = $class;
    }

    public function get(string $key): ?string
    {
        return $this->charts[$key] ?? null;
    }

    public function make(string $key)
    {
        if (! $this->get($key)) {
            return null;
        }

        $class = $this->get($key);

        return app()->make($class);
    }

    /**
     * Get all dashboard charts.
     *
     * @return array<string, string>
     */
    public function charts(): array
    {
        return $this->charts;
    }
}
