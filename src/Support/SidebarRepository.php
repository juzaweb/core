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

use Illuminate\Support\Collection;
use Juzaweb\Modules\Core\Contracts\Sidebar;

class SidebarRepository implements Sidebar
{
    protected array $sidebars = [];

    public function make(string $key, callable $callback): void
    {
        $this->sidebars[$key] = $callback;
    }

    public function all(): Collection
    {
        return collect($this->sidebars)->map(
            function ($callback, $key) {
                return new \Juzaweb\Modules\Core\Support\Entities\Sidebar($key, $callback($key));
            }
        );
    }
}
