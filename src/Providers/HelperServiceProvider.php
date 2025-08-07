<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Providers;

use Carbon\Carbon;

class HelperServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Carbon::macro(
            'toUserTimezone',
            function () {
                $tz = auth()->user()?->timezone ?? config('app.timezone');
                return $this->copy()->setTimezone($tz);
            }
        );
    }
}
