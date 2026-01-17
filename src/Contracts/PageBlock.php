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

interface PageBlock
{
    public function make(string $key, callable $callback): void;

    public function get(string $key): ?\Juzaweb\Modules\Core\Support\Entities\PageBlock;

    public function all(): \Illuminate\Support\Collection;
}
