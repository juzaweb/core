<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/25/2021
 * Time: 10:29 PM
 */

namespace Juzaweb\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Core\Http\Middleware\XFrameHeadersMiddleware;
use Illuminate\Support\Facades\URL;

class PerformanceServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootMiddlewares();
        $this->bootSchemeSsl();
    }

    protected function bootMiddlewares()
    {
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', XFrameHeadersMiddleware::class);
    }

    protected function bootSchemeSsl()
    {
        if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if ($_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
                URL::forceScheme('https');
            }
        } else {
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
                URL::forceScheme('https');
            }
        }
    }
}
