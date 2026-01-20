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

/**
 * @method static void chart(string $key, string $class)
 * @method static string|null get(string $key)
 * @method static mixed make(string $key)
 * @method static array charts()
 * @see \Juzaweb\Modules\Core\Support\ChartRepository
 */
class Chart extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Juzaweb\Modules\Core\Contracts\Chart::class;
    }
}
