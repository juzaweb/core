<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array|bool|string lookup(string $ip, array|int $fields = null)
 * @method static bool|string countryCode(string $ip)
 */
class IP2Location extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Translations\Contracts\IP2Location::class;
    }
}
