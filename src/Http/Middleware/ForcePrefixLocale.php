<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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
