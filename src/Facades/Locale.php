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
use Juzaweb\Modules\Core\Contracts\Locale as LocaleContract;

/**
 * @method static string setLocale()
 * @see \Juzaweb\Modules\Core\Support\LocaleRepository
 */
class Locale extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return LocaleContract::class;
    }
}
