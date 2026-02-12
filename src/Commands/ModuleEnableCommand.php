<?php

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Facades\Module as ModuleFacade;
use Juzaweb\Modules\Core\Modules\Module;
use Symfony\Component\Console\Input\InputArgument;

class ModuleEnableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:enable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified module.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Enabling module ...');

        if ($name = $this->argument('module') ) {
            $this->enable($name);

            return 0;
        }

        $this->enableAll();

        return 0;
    }

    /**
     * enableAll
     *
     * @return void
     */
    public function enableAll()
    {
        /** @var Module[] $modules */
        $modules = ModuleFacade::all();

        foreach ($modules as $module) {
            $this->enable($module);
        }
    }

    /**
     * enable
     *
     * @param string|Module $name
     * @return void
     */
    public function enable($name)
    {
        if ($name instanceof Module) {
            $module = $name;
        }else {
            $module = ModuleFacade::findOrFail($name);
        }

        if ($module->isDisabled()) {
            $module->enable();

            $this->components->info("Module [{$module}] enabled successful.");
        }else {
            $this->components->warn("Module [{$module}] has already enabled.");
        }

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['module', InputArgument::OPTIONAL, 'Module name.'],
        ];
    }
}
