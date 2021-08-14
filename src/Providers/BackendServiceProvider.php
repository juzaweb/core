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
 * Date: 6/19/2021
 * Time: 6:31 PM
 */

namespace Juzaweb\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Core\Http\Middleware\Admin;
use Illuminate\Routing\Router;
use Juzaweb\Core\Macros\RouterMacros;
use Juzaweb\Core\Facades\HookAction;

class BackendServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootMiddlewares();
        HookAction::loadActionForm(__DIR__ . '/../../actions');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'juzaweb');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'juzaweb');
    }

    public function register()
    {
        $this->registerRouteMacros();
    }

    protected function bootMiddlewares()
    {
        $this->app['router']->aliasMiddleware('admin', Admin::class);
    }

    protected function registerRouteMacros()
    {
        Router::mixin(new RouterMacros());
    }
}