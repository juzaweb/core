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
use Juzaweb\Modules\Core\Contracts\Chart as ChartContract;

/**
 * @method static void chart(string $name, string $chart)
 * @method static array<string, string> charts()
 * @method static ?string get(string $name)
 * @method static mixed make(string $name)
 */
class Chart extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ChartContract::class;
    }
}
