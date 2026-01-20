<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Contracts;

interface Chart
{
    public function chart(string $key, string $class): void;

    public function get(string $key): ?string;

    public function make(string $key);

    /**
     * Get all dashboard charts.
     *
     * @return array<string, string>
     */
    public function charts(): array;
}
