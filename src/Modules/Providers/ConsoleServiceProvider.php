<?php

namespace Juzaweb\Core\Modules\Providers;

use Illuminate\Support\ServiceProvider;
use Juzaweb\Core\Modules\Commands;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * The available commands
     * @var array
     */
    protected array $commands = [
        Commands\CommandMakeCommand::class,
        Commands\ControllerMakeCommand::class,
        Commands\DisableCommand::class,
        Commands\DumpCommand::class,
        Commands\EnableCommand::class,
        Commands\EventMakeCommand::class,
        Commands\JobMakeCommand::class,
        Commands\ListenerMakeCommand::class,
        Commands\MailMakeCommand::class,
        Commands\MiddlewareMakeCommand::class,
        Commands\NotificationMakeCommand::class,
        Commands\ProviderMakeCommand::class,
        Commands\RouteProviderMakeCommand::class,
        Commands\InstallCommand::class,
        Commands\ListCommand::class,
        Commands\ModuleDeleteCommand::class,
        Commands\ModuleMakeCommand::class,
        Commands\Databases\FactoryMakeCommand::class,
        Commands\PolicyMakeCommand::class,
        Commands\RequestMakeCommand::class,
        Commands\RuleMakeCommand::class,
        Commands\Databases\MigrateCommand::class,
        Commands\Databases\MigrateRefreshCommand::class,
        Commands\Databases\MigrateResetCommand::class,
        Commands\Databases\MigrateFreshCommand::class,
        Commands\Databases\MigrateRollbackCommand::class,
        Commands\Databases\MigrateStatusCommand::class,
        Commands\Databases\MigrationMakeCommand::class,
        Commands\ModelMakeCommand::class,
        Commands\ModelShowCommand::class,
        Commands\PublishCommand::class,
        Commands\PublishConfigurationCommand::class,
        Commands\PublishMigrationCommand::class,
        Commands\PublishTranslationCommand::class,
        Commands\Databases\SeedCommand::class,
        Commands\Databases\SeedMakeCommand::class,
        Commands\SetupCommand::class,
        Commands\UnUseCommand::class,
        Commands\UpdateCommand::class,
        Commands\UseCommand::class,
        Commands\ResourceMakeCommand::class,
        Commands\TestMakeCommand::class,
        Commands\LaravelModulesV6Migrator::class,
        Commands\ComponentClassMakeCommand::class,
        Commands\ComponentViewMakeCommand::class,
        Commands\RepositoryMakeCommand::class,
        Commands\RepositoryEloquentMakeCommand::class,
        Commands\DatatableMakeCommand::class,
        Commands\FormMakeCommand::class,
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
