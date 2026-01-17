<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Middleware;

use Illuminate\Http\Request;
use Juzaweb\Modules\Core\Translations\Facades\IP2Location;
use Juzaweb\Modules\Core\Translations\Models\Language;

class RedirectLanguage
{
    public function handle(Request $request, \Closure $next): mixed
    {
        $multipleLanguage = setting('multiple_language', 'none');
        $config = setting('redirect_language', 'browser');
        if ($multipleLanguage == 'none' || $config == 'none') {
            return $next($request);
        }

        if ($locale = $request->get('hl')) {
            app()->setLocale($locale);

            // Set the locale in the session if needed
            session(['locale' => $locale]);
        }

        if ($multipleLanguage == 'session') {
            if (session('locale')) {
                // If the locale is set in the session, use it
                app()->setLocale(session('locale'));
            }

            return $next($request);
        }

        // Case force set locale in session
        if ($multipleLanguage == 'prefix'
            && session()->has('locale')
            && session('locale') == app()->getLocale()
        ) {
            return $next($request);
        }

        $locale = null;
        $locales = Language::languages()->keys()->toArray();

        if ($config == 'ip') {
            $ip = client_ip();
            $countryCode = strtolower(IP2Location::countryCode($ip));
            $locale = config("countries.{$countryCode}.language");
        }

        if ($config == 'browser') {
            $browserLocale = $request->getPreferredLanguage();
            $locale = explode('_', str_replace('-', '_', $browserLocale ?? ''))[0];
        }

        if (! $locale || ! in_array($locale, $locales) || $locale == app()->getLocale()) {
            return $next($request);
        }

        if ($multipleLanguage == 'prefix' && $request->is('/')) {
            return redirect()->to("/{$locale}");
        }

        if ($multipleLanguage == 'subdomain' && $request->is('/')) {
            $host = $request->getHost();
            $parts = explode('.', $host);
            $subdomain = $parts[0];

            if (in_array($subdomain, $locales)) {
                if ($subdomain == $locale) {
                    return $next($request);
                }

                $parts[0] = $locale;
                $newHost = implode('.', $parts);
            } else {
                $newHost = "{$locale}.{$host}";
            }

            return redirect()->to($request->getScheme().'://'.$newHost.$request->getRequestUri());
        }

        return $next($request);
    }
}
