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
use Juzaweb\Modules\Core\Contracts\Setting as SettingContract;

/**
 * @method static mixed get(string $key, mixed $default = null)
 * @method static \Juzaweb\Modules\Core\Models\Setting set(string $key, mixed $value)
 * @method static Collection sets(array $settings)
 * @method static array gets(array $keys, mixed $default = null)
 * @method static bool|null boolean(string $key, mixed $default = null)
 * @method static int|null integer(string $key, mixed $default = null)
 * @method static float|null float(string $key, mixed $default = null)
 * @method static Collection all()
 * @method static Collection keys(?array $keys = null)
 * @method static Collection settings(?string $key = null)
 * @method static Collection configs()
 * @see \Juzaweb\Modules\Core\Support\SettingRepository
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
