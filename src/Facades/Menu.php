<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Juzaweb\Core\Support\Entities\Menu make(string $key, ?string $title = null)
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Core\Contracts\Menu::class;
    }
}
