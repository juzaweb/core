<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Providers;

use Juzaweb\Modules\Core\Contracts\Theme;
use Juzaweb\Modules\Core\Themes\ThemeRepository;

class ThemeServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands(
            [
                \Juzaweb\Modules\Core\Themes\Commands\ThemeListCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\ThemeActiveCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\ThemePublishCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\ThemeGeneratorCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\MakePageBlockCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\MakeTemplateCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\MakeViewCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\MakeControllerCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\DownloadStyleCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\DownloadTemplateCommand::class,
                \Juzaweb\Modules\Core\Themes\Commands\ThemeSeedCommand::class,
            ]
        );
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
