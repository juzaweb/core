<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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
