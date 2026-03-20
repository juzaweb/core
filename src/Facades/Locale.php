<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Juzaweb\Modules\Core\Contracts\Locale as LocaleContract;
use Juzaweb\Modules\Core\Support\LocaleRepository;

/**
 * @method static string|null setLocale(string|null $locale = null)
 *
 * @see LocaleRepository
 */
class Locale extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return LocaleContract::class;
    }
}
