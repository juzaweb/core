<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Contracts;

use Illuminate\Support\Collection;

/**
 * @see \Juzaweb\Modules\Core\Support\MenuRepository
 */
interface Menu
{
    public function make(string $key, callable $callback): void;

    public function get(string $key): ?array;

    public function getByPosition(string $position): Collection;

    public function all(): Collection;
}
