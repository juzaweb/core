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

class OnlyMainSite
{
    public function handle(Request $request, \Closure $next): mixed
    {
        if ($request->getHost() !== config('network.domain')) {
            abort(404, __('core::translation.page_not_found'));
        }

        return $next($request);
    }
}
