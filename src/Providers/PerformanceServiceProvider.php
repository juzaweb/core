<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://github.com/juzaweb/cms
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Modules\Core\Support\BladeMinify\BladeMinifyCompiler;

class PerformanceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (config('core.optimize.minify_views')) {
            $this->registerBladeCompiler();
        }
    }

    protected function registerBladeCompiler(): void
    {
        $this->app->singleton(
            'blade.compiler',
            function ($app) {
                return new BladeMinifyCompiler($app['files'], $app['config']['view.compiled']);
            }
        );
    }
}
