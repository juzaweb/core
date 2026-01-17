<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Modules\Core\Modules\Commands\Cruds;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Modules\Traits\UseFromModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrudMakeCommand extends Command
{
    use UseFromModel;

    protected $name = 'module:make-crud';

    protected $description = 'Make crud for the specified module.';

    public function handle(): int
    {
        $this->call('module:make-admin-crud', [
            'model' => $this->argument('model'),
            'module' => $this->argument('module'),
            '--force' => $this->option('force'),
            ...$this->fromModelOptions,
        ]);

        if ($this->option('api')) {
            $this->call('module:make-api-crud', [
                'model' => $this->argument('model'),
                'module' => $this->argument('module'),
                '--force' => $this->option('force'),
                ...$this->fromModelOptions,
            ]);
        }

        return static::SUCCESS;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['model', InputArgument::REQUIRED, 'The name model for the crud.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ...$this->fromModelOptions,
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the class already exists'],
            ['api', null, InputOption::VALUE_NONE, 'Generate api crud'],
        ];
    }
}
