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
 * @method static \Juzaweb\Modules\Core\Themes\Theme|null current()
 * @method static \Juzaweb\Modules\Core\Themes\Theme|null find(string $name)
 * @method static Collection<\Juzaweb\Modules\Core\Themes\Theme> all()
 * @method static \Juzaweb\Modules\Core\Themes\Theme findOrFail(string $name)
 * @method static bool has(string $name)
 * @method static bool activate(string $theme)
 * @method static void init()
 * @see \Juzaweb\Modules\Core\Themes\ThemeRepository
 */
class Theme extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\Theme::class;
    }
}
