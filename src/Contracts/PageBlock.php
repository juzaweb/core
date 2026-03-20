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

use Illuminate\Support\Collection;

interface PageBlock
{
    public function make(string $key, callable $callback): void;

    public function get(string $key): ?\Juzaweb\Modules\Core\Support\Entities\PageBlock;

    public function all(): Collection;
}
