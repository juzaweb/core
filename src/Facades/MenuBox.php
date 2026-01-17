<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Contracts\MenuBox as MenuBoxContract;
use Juzaweb\Modules\Core\Support\MenuBoxRepository;

/**
 * @method static void make(string $key, string $class, callable $options)
 * @method static array get(string $position)
 * @method static Collection all()
 * @see MenuBoxRepository
 */
class MenuBox extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MenuBoxContract::class;
    }
}
