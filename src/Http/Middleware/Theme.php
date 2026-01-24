<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Facades\Thumbnail;

class Theme
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $defaultThubnails = Thumbnail::getDefaults();

        foreach ($defaultThubnails as $class => $defaultThubnail) {
            $class::defaultThumbnail($defaultThubnail);
        }

        return $next($request);
    }
}
