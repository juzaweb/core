<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Providers;

use App\Providers\ServiceProvider;
use Juzaweb\Core\Contracts\Theme;
use Juzaweb\Core\Themes\Commands;
use Juzaweb\Core\Themes\ThemeRepository;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands(
            [
                Commands\ThemeListCommand::class,
                Commands\ThemeActiveCommand::class,
                Commands\ThemePublishCommand::class,
            ]
        );

        $this->app[Theme::class]->init();
    }

    public function register(): void
    {
        // Registers the eventy singleton.
        $this->app->singleton(
            Theme::class,
            function ($app) {
                return new ThemeRepository($app, $app['config']->get('themes.path'));
            }
        );
    }
}
