<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Support\WidgetRepository;

/**
 * @method static void make(string $key, callable $callback)
 * @method static null|\Juzaweb\Modules\Core\Support\Entities\Widget get(string $key)
 * @method static \Illuminate\Support\Collection all()
 *
 * @see WidgetRepository
 */
class Widget extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\Widget::class;
    }
}
