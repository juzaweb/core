<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Http\Middleware;

use Illuminate\Http\Request;

class ForcePrefixLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($locale = $this->getLocaleInPath($request)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    protected function getLocaleInPath(Request $request): ?string
    {
        $locale = explode('/', $request->path())[0];

        if (in_array($locale, config('translatable.locales'))) {
            return $locale;
        }

        return null;
    }
}
