<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Core\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Juzaweb\Core\Contracts\GlobalData as GlobalDataContract;

/**
 * @method static void set($key, $value)
 * @method static void push($key, $value)
 * @method static mixed get(string $key, array $default = [])
 * @method static Collection collect(string $key, array $default = [])
 * @see \Juzaweb\Core\Support\GlobalDataRepository
 */
class GlobalData extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return GlobalDataContract::class;
    }
}
