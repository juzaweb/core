<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Themes\Providers;

use Juzaweb\Modules\Core\Contracts\Theme;
use Juzaweb\Modules\Core\Providers\ServiceProvider;
use Juzaweb\Modules\Core\Themes\Activators\SettingActivator;
use Juzaweb\Modules\Core\Themes\Commands\DownloadStyleCommand;
use Juzaweb\Modules\Core\Themes\Commands\DownloadTemplateCommand;
use Juzaweb\Modules\Core\Themes\Commands\MakeControllerCommand;
use Juzaweb\Modules\Core\Themes\Commands\MakePageBlockCommand;
use Juzaweb\Modules\Core\Themes\Commands\MakeTemplateCommand;
use Juzaweb\Modules\Core\Themes\Commands\MakeViewCommand;
use Juzaweb\Modules\Core\Themes\Commands\ThemeActiveCommand;
use Juzaweb\Modules\Core\Themes\Commands\ThemeGeneratorCommand;
use Juzaweb\Modules\Core\Themes\Commands\ThemeListCommand;
use Juzaweb\Modules\Core\Themes\Commands\ThemePublishCommand;
use Juzaweb\Modules\Core\Themes\Commands\ThemeSeedCommand;
use Juzaweb\Modules\Core\Themes\Contracts\ThemeActivatorInterface;
use Juzaweb\Modules\Core\Themes\ThemeRepository;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands(
            [
                ThemeListCommand::class,
                ThemeActiveCommand::class,
                ThemePublishCommand::class,
                ThemeGeneratorCommand::class,
                MakePageBlockCommand::class,
                MakeTemplateCommand::class,
                MakeViewCommand::class,
                MakeControllerCommand::class,
                DownloadStyleCommand::class,
                DownloadTemplateCommand::class,
                ThemeSeedCommand::class,
            ]
        );

        if ($this->app['config']->get('themes.enable')) {
            $this->app[Theme::class]->init();
        }
    }

    public function register(): void
    {
        // Register theme activator
        $this->app->singleton(
            ThemeActivatorInterface::class,
            function ($app) {
                $activator = $app['config']->get('themes.activator');
                $class = $app['config']->get('themes.activators.' . $activator)['class'];

                if ($class === null) {
                    throw new \InvalidArgumentException('Theme activator class not configured');
                }

                return new $class($app);
            }
        );

        // Registers the theme repository singleton.
        $this->app->singleton(
            Theme::class,
            function ($app) {
                return new ThemeRepository($app, $app['config']->get('themes.path'));
            }
        );
    }
}
