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
 * Time: 3:05 PM
 */

namespace Juzaweb\Core\Http\Middleware;

use Closure;

class Theme
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}