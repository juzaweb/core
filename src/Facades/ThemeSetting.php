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

/**
 * @method static \Juzaweb\Modules\Core\Support\Entities\ThemeSetting make(string $key)
 * @method static mixed get(string $key, $default = null)
 * @method static bool|null boolean(string $key, mixed $default = null)
 * @method static int|null integer(string $key, mixed $default = null)
 * @method static float|null float(string $key, mixed $default = null)
 * @method static \Juzaweb\Modules\Core\Models\ThemeSetting set(string $key, $value = null)
 * @method static array gets(array $keys, $default = null)
 * @method static Collection sets(array $values)
 * @method static Collection all()
 * @method static Collection keys(?array $keys = null)
 * @method static Collection settings(?string $key = null)
 * @method static Collection configs()
 * @see \Juzaweb\Modules\Core\Support\ThemeSettingRepository
 */
class ThemeSetting extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\ThemeSetting::class;
    }
}
