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
            ]);
        }
    }
}
