<?php

namespace Juzaweb\Core\Modules\Commands;

use Illuminate\Database\Console\ShowModelCommand;

class ModelShowCommand extends ShowModelCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:model-show';

    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'module:model-show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show information about an Eloquent model in modules';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'module:model-show {model : The model to show}
                {--database= : The database connection to use}
                {--json : Output the model as JSON}';


    /**
     * Qualify the given model class base name.
     *
     * @param string $model
     * @return string
     *
     * @see \Illuminate\Console\GeneratorCommand
     */
    protected function qualifyModel(string $model): string
    {
        if (str_contains($model, '\\') && class_exists($model)) {
            return $model;
        }

        $rootNamespace = config('modules.namespace');

        $modelPath = glob($rootNamespace . DIRECTORY_SEPARATOR .
            '*' . DIRECTORY_SEPARATOR .
            config('modules.paths.generator.model.path') . DIRECTORY_SEPARATOR .
            "$model.php");

        if (!count($modelPath)) {
            return $model;
        }

        return str_replace(['/', '.php'], ['\\', ''], $modelPath[0]);
    }

}
