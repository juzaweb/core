<?php

namespace Juzaweb\Modules\Core\Modules\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Juzaweb\Modules\Core\Modules\Traits\UseFromModel;
use Juzaweb\Modules\Core\Translations\Contracts\Translatable;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RequestMakeCommand extends GeneratorCommand
{
    use UseFromModel;

    /**
     * The name of argument name.
     *
     * @var string
     */
    protected string $argumentName = 'name';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new form request class for the specified module.';

    protected string $argumentSubfix = 'Request';

    protected array $rules = [];

    protected function preGenerate(): bool
    {
        $module = app(RepositoryInterface::class)->findOrFail($this->getModuleName());

        try {
            /** @var Model $model */
            $model = app($this->getModelClass($module));
        } catch (BindingResolutionException $e) {
            if ($this->option('for-model')) {
                $this->error("Model [{$this->option('for-model')}] does not exists.");

                return false;
            }

            return true;
        }

        $columns = $model->getFillable();

        foreach ($columns as $column) {
            $this->rules[] = "'{$column}' => ['required']";
        }

        if ($model instanceof Translatable) {
            $columns = array_diff($model->getTranslatedFields(), ['id', 'created_at', 'updated_at', 'locale']);

            foreach ($columns as $column) {
                $this->rules[] = "'$column' => ['required']";
            }
        }

        return true;
    }

    public function getDefaultNamespace(): string
    {
        return app(RepositoryInterface::class)->config('paths.generator.request.namespace', 'Http/Requests');
    }

    /**
     * @return string
     */
    protected function getTemplateContents(): string
    {
        $module = app(RepositoryInterface::class)->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClass(),
            'TABLE' => $this->getTableName($module),
            'RULES' => $this->getRules(),
            'MODEL_NAME' => $this->getModelName(),
        ]))->render();
    }

    protected function getRules(): string
    {
        return "[\n\t\t\t".implode(",\n\t\t\t", $this->rules)."\n\t\t]";
    }

    /**
     * @return string
     */
    protected function getDestinationFilePath(): string
    {
        $path = app(RepositoryInterface::class)->getModulePath($this->getModuleName());

        $requestPath = GenerateConfigReader::read('request');

        return $path.$requestPath->getPath().'/'.$this->getFileName().'.php';
    }

    protected function getStubName(): string
    {
        if ($this->option('bulk')) {
            return '/requests/bulk-request.stub';
        }

        return '/requests/request.stub';
    }

    /**
     * @return string
     */
    protected function getFileName(): string
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the form request class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ...$this->fromModelOptions,
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the model already exists.'],
            ['bulk', 'b', InputOption::VALUE_NONE, 'Create a bulk actions request.'],
        ];
    }
}
