<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Juzaweb\Core\Support\Entities\Menu make(string $key, ?string $title = null)
 * @method static Collection get(string $position)
 */
class Menu extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Core\Contracts\Menu::class;
    }
}
