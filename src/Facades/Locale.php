<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Core\Contracts\Locale as LocaleContract;

/**
 * @method static string setLocale()
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