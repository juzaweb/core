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
 * @method static Collection all()
 * @method static mixed get(string $key, $default = null)
 * @method static void set(string $key, $value = null)
 * @method static array gets(array $keys, $default = null)
 * @method static void sets(array $values)
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
