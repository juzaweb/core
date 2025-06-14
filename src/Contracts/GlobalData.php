<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Contracts;

use Illuminate\Support\Collection;

interface GlobalData
{
    public function set(string $key, array $value): void;

    public function get(string $key, array $default = []);

    public function all(): Collection;

    public function push($key, $value): void;

    public function collect(string $key, array $default = []): Collection;
}
