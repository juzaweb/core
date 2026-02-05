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
 * @method static array getPermissions()
 * @see \Juzaweb\Modules\Core\Support\PermissionManager
 */
class PermissionManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\PermissionManager::class;
    }
}
