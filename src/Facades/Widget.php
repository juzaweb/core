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

use Illuminate\Support\Facades\Facade;

/**
 * @method static void make(string $key, callable $callback)
 * @method static null|\Juzaweb\Modules\Core\Support\Entities\Widget get(string $key)
 * @method static \Illuminate\Support\Collection all()
 * @see \Juzaweb\Modules\Core\Support\SidebarRepository
 */
class Widget extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\Widget::class;
    }
}
