<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Http\Middleware;

use Illuminate\Http\Request;
use Juzaweb\Core\Models\Language;

class MultipleLanguage
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
        if ($locale = $this->getLocaleFromRequest($request)) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    protected function getLocaleFromRequest(Request $request): ?string
    {
        $config = setting('multiple_language', 'none');
        $defaultLocale = Language::default();

        // Check for 'hl' parameter in the request
        if ($config == 'session') {
            if ($hl = $request->get('hl')) {
                // session()->put('locale', $hl);
                return $hl;
            }

            return session('locale', $defaultLocale);
        }

        if ($config == 'prefix') {
            return $this->getLocaleInPath($request);
        }

        if ($config == 'subdomain') {
            $subdomain = explode('.', $request->getHost())[0];

            if (in_array($subdomain, Language::languages()->keys()->toArray())) {
                return $subdomain;
            }
        }

        return null;
    }

    protected function getLocaleInPath(Request $request): ?string
    {
        $locale = explode('/', $request->path())[0];

        if (in_array($locale, Language::languages()->keys()->toArray())) {
            return $locale;
        }

        return null;
    }
}
