<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Core\Contracts\Setting as SettingContract;

/**
 * @method static \Juzaweb\Core\Support\Entities\Setting make(string $key)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static bool|null boolean(string $key, mixed $default = null)
 * @method static int|null integer(string $key, mixed $default = null)
 * @method static \Illuminate\Support\Collection settings()
 * @see \Juzaweb\Core\Support\Entities\Setting
 */
class Setting extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SettingContract::class;
    }
}
