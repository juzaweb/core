<?php

namespace Juzaweb\Modules\Core\Modules\Providers;

use App\Modules\Commands;
use Illuminate\Support\ServiceProvider;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The available commands
     * @var array
     */
    protected array $commands = [
        \Juzaweb\Modules\Core\Modules\Commands\CommandMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ControllerMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\DisableCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\DumpCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\EnableCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\EventMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\JobMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ListenerMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\MailMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\MiddlewareMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\NotificationMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ProviderMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\RouteProviderMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\InstallCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ListCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ModuleDeleteCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ModuleMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\FactoryMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\PolicyMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\RequestMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\RuleMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\MigrateCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\MigrateRefreshCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\MigrateResetCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\MigrateFreshCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\MigrateRollbackCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\MigrateStatusCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\MigrationMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ModelMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ModelShowCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\PublishCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\PublishConfigurationCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\PublishMigrationCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\PublishTranslationCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\SeedCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Databases\SeedMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\SetupCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\UnUseCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\UpdateCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\UseCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ResourceMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\TestMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ComponentClassMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\ComponentViewMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\RepositoryMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\RepositoryEloquentMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\DatatableMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\LinkCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Cruds\AdminCrudMakeCommand::class,
        \Juzaweb\Modules\Core\Modules\Commands\Cruds\CrudMakeCommand::class,
    ];

    public function register(): void
    {
        $this->commands($this->commands);
    }

    public function provides(): array
    {
        return $this->commands;
    }
}
