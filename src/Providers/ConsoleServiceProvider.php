<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Providers;

class ConsoleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Juzaweb\Modules\Core\Commands\CacheSizeCommand::class,
                \Juzaweb\Modules\Core\Commands\ClearLogCommand::class,
                \Juzaweb\Modules\Core\Commands\MakeUserCommand::class,
                \Juzaweb\Modules\Core\Commands\PublishCoreCommand::class,
                \Juzaweb\Modules\Core\Commands\TestMailCommand::class,
                \Juzaweb\Modules\Core\Commands\ModuleInstallCommand::class,
                \Juzaweb\Modules\Core\Commands\ModuleDisableCommand::class,
                \Juzaweb\Modules\Core\Commands\ModuleEnableCommand::class,
                \Juzaweb\Modules\Core\Commands\ModuleListCommand::class,
                \Juzaweb\Modules\Core\Commands\UpdateCommand::class,
                \Juzaweb\Modules\Core\Commands\ModuleLinkCommand::class,
                // Theme commands
                \Juzaweb\Modules\Core\Commands\ThemeInstallCommand::class,
                \Juzaweb\Modules\Core\Commands\ThemeListCommand::class,
                \Juzaweb\Modules\Core\Commands\ThemePublishCommand::class,
                \Juzaweb\Modules\Core\Commands\ThemeActiveCommand::class,
                \Juzaweb\Modules\Core\Commands\ThemeUpdateCommand::class,
            ]);
        }
    }
}
