<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Juzaweb\Core\Themes\Theme|null current()
 * @method static \Juzaweb\Core\Themes\Theme|null find(string $name)
 * @method static \Juzaweb\Core\Themes\Theme findOrFail(string $name): Theme
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
        return \Juzaweb\Core\Contracts\Theme::class;
    }
}
