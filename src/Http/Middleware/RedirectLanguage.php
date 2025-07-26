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
use Juzaweb\Translations\Facades\IP2Location;

class RedirectLanguage
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $multipleLanguage = setting('multiple_language', 'none');
        if (in_array($multipleLanguage, ['none', 'session'])) {
            return $next($request);
        }

        $config = setting('redirect_language', 'none');
        if ($config == 'none') {
            return $next($request);
        }

        $locale = null;
        if ($config == 'ip') {
            $ip = client_ip();
            $countryCode = strtolower(IP2Location::countryCode($ip));
            $locale = config("countries.{$countryCode}.language");
        }

        if ($config == 'browser') {
            $browserLocale = $request->getPreferredLanguage();
            $locale = explode('_', $browserLocale ?? '')[0];
        }

        if ($locale
            && $locale != app()->getLocale()
            && in_array($locale, Language::languages()->keys()->toArray())
        ) {
            if ($multipleLanguage == 'prefix' && $request->is('/')) {
                return redirect()->to("/{$locale}");
            }

            if ($multipleLanguage == 'subdomain') {
                $host = $request->getHost();
                $newHost = "{$locale}.{$host}";
                return redirect()->to($request->getScheme() . '://' . $newHost . $request->getRequestUri());
            }
        }

        return $next($request);
    }
}
