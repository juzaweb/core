<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Support;

use Illuminate\Support\Collection;
use Juzaweb\Core\Contracts\GlobalData;
use Juzaweb\Core\Contracts\Menu as MenuContract;
use Juzaweb\Core\Support\Entities\Menu;
use Juzaweb\Hooks\Contracts\Hook;

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
