<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Juzaweb\Core\Contracts\GlobalData;
use Juzaweb\Core\Contracts\Hook;
use Juzaweb\Core\Contracts\Menu as MenuAlias;
use Juzaweb\Core\Support\Entities\Menu;

class MenuRepository implements MenuAlias
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
}
