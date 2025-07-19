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
use Juzaweb\Core\Contracts\DashboardBox;
use Juzaweb\Core\Contracts\Dashboard as DashboardContract;

/**
 * @method static void box(string $name, DashboardBox $box)
 * @method static array<DashboardBox> boxes()
 */
class Dashboard extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return DashboardContract::class;
    }
}
