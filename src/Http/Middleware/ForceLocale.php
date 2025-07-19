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

class ForceLocale
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
        if ($locale = $request->get('hl')) {
            app()->setLocale($locale);

            // Set the locale in the session if needed
            session(['locale' => $locale]);
        } elseif (session('locale')) {
            // If the locale is set in the session, use it
            app()->setLocale(session('locale'));
        }

        return $next($request);
    }
}
