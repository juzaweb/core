<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void make(string $key, null|string|callable $title = null)
 * @method static array|null get(string $key)
 * @method static Collection getByPosition(string $position)
 * @see \Juzaweb\Modules\Core\Support\MenuRepository
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\Menu::class;
    }
}
