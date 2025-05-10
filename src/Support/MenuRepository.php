<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Illuminate\Support\Collection;
use Juzaweb\Core\Contracts\GlobalData;
use Juzaweb\Core\Contracts\Hook;
use Juzaweb\Core\Contracts\Menu as MenuContract;
use Juzaweb\Core\Support\Entities\Menu;

class MenuRepository implements MenuContract
{
    public function __construct(
        protected GlobalData $globalData,
        protected Hook $hook,
    ) {
    }
    
    public function make(string $key, ?string $title = null): Menu
    {
        return new Menu($this->globalData, $this->hook, $key, $title);
    }

    public function get(string $position): Collection
    {
        return new Collection($this->globalData->get("menus.{$position}"));
    }
}
