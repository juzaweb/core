<?php
/**
 * MYMO CMS - The Best Laravel CMS
 *
 * @package    mymocms/mymocms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/mymocms/mymocms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 8/12/2021
 * Time: 4:22 PM
 */

namespace Juzaweb\Core;

use Illuminate\Support\Facades\Route;

class Core
{
    protected static $namespace = 'Juzaweb\Core\Http\Controllers';

    public static function adminRoutes()
    {
        Route::namespace(self::$namespace)
            ->group(__DIR__ . '/routes/admin.php');
    }

    public static function webRoutes()
    {
        Route::namespace(self::$namespace)
            ->group(__DIR__ . '/routes/web.php');
    }

    public static function apiRoutes()
    {
        Route::namespace(self::$namespace)
            ->group(__DIR__ . '/routes/api.php');
    }
}