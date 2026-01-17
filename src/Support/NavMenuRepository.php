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
use Juzaweb\Modules\Core\Contracts\NavMenu;

class NavMenuRepository implements NavMenu
{
    protected array $navMenus = [];

    public function make(string $key, callable $callback): void
    {
        $this->navMenus[$key] = $callback;
    }

    public function get(string $key): ?array
    {
        if ($template = data_get($this->navMenus, $key)) {
            return $template();
        }

        return null;
    }

    public function all(): Collection
    {
        return collect($this->navMenus)->map(
            function ($callback, $key) {
                return $callback();
            }
        );
    }
}
