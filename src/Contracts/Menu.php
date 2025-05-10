<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Contracts;

use Illuminate\Support\Collection;
use Juzaweb\Core\Support\MenuRepository;

/**
 * @see MenuRepository
 */
interface Menu
{
    public function make(string $key, ?string $title = null): \Juzaweb\Core\Support\Entities\Menu;

    public function get(string $position): Collection;
}
