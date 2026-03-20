<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Providers;

use Juzaweb\Modules\Core\Commands\CacheSizeCommand;
use Juzaweb\Modules\Core\Commands\ClearLogCommand;
use Juzaweb\Modules\Core\Commands\MakeUserCommand;
use Juzaweb\Modules\Core\Commands\ModuleDisableCommand;
use Juzaweb\Modules\Core\Commands\ModuleEnableCommand;
use Juzaweb\Modules\Core\Commands\ModuleInstallCommand;
use Juzaweb\Modules\Core\Commands\ModuleLinkCommand;
use Juzaweb\Modules\Core\Commands\ModuleListCommand;
use Juzaweb\Modules\Core\Commands\ModuleUpdateCommand;
use Juzaweb\Modules\Core\Commands\PublishCoreCommand;
use Juzaweb\Modules\Core\Commands\SendMediaToCloudCommand;
use Juzaweb\Modules\Core\Commands\TestMailCommand;
use Juzaweb\Modules\Core\Commands\ThemeActiveCommand;
use Juzaweb\Modules\Core\Commands\ThemeInstallCommand;
use Juzaweb\Modules\Core\Commands\ThemeListCommand;
use Juzaweb\Modules\Core\Commands\ThemePublishCommand;
use Juzaweb\Modules\Core\Commands\ThemeUpdateCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            CacheSizeCommand::class,
            ClearLogCommand::class,
            MakeUserCommand::class,
            PublishCoreCommand::class,
            TestMailCommand::class,
            SendMediaToCloudCommand::class,
            ModuleInstallCommand::class,
            ModuleDisableCommand::class,
            ModuleEnableCommand::class,
            ModuleListCommand::class,
            ModuleUpdateCommand::class,
            ModuleLinkCommand::class,
            // Theme commands
            ThemeInstallCommand::class,
            ThemeListCommand::class,
            ThemePublishCommand::class,
            ThemeActiveCommand::class,
            ThemeUpdateCommand::class,
        ]);
    }
}
