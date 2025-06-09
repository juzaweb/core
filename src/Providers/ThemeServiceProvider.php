<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Providers;

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
                Commands\ThemeGeneratorCommand::class,
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
