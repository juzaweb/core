<?php

namespace Juzaweb\Core\Modules\Commands;

use Illuminate\Support\Str;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Core\Modules\Support\Stub;
use Juzaweb\Core\Modules\Traits\UseFromModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ResourceMakeCommand extends GeneratorCommand
{
    use UseFromModel;

    protected string $argumentName = 'name';

    protected $name = 'module:make-resource';

    protected $description = 'Create a new resource class for the specified module.';

    protected bool $withModel = false;

    protected array $argumentSubfix = ['Resource', 'Collection'];

    protected array $fields = [];

    public function getDefaultNamespace(): string
    {
        return app(RepositoryInterface::class)->config(
            'paths.generator.resource.namespace',
            'Http\\Resources'
        );
    }

    public function preGenerate(): bool
    {
        if ($this->option('for-model')) {
            $this->withModel = true;
        }

        $module = app(RepositoryInterface::class)->findOrFail($this->getModuleName());

        $model = $this->makeModel($module);

        if ($model === false) {
            return false;
        }

        $this->mapFieldsFromModel($model);

        if ($this->fields) {
            $this->withModel = true;
        }

        return true;
    }

    protected function mapFieldsFromModel(Model $model): void
    {
        $columns = $this->getAllModelColumns($model);

        if ($this->collection()) {
            $this->fields[] = "'id' => \$item->id";
        } else {
            $this->fields[] = "'id' => \$this->resource->id";
        }

        foreach ($columns as $column) {
            if ($this->collection()) {
                $this->fields[] = "'{$column}' => \$item->{$column}";
                continue;
            }

            $this->fields[] = "'{$column}' => \$this->resource->{$column}";
        }

        if ($model->timestamps) {
            if ($this->collection()) {
                $this->fields[] = "'created_at' => \$item->created_at";
                $this->fields[] = "'updated_at' => \$item->updated_at";
            } else {
                $this->fields[] = "'created_at' => \$this->resource->created_at";
                $this->fields[] = "'updated_at' => \$this->resource->updated_at";
            }
        }
    }

    protected function getFields(): string
    {
        return "[\n\t\t\t\t\t".implode(",\n\t\t\t\t\t", $this->fields)."\n\t\t\t\t]";
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
            'MODEL_NAME' => $this->getModelName(),
            'MODEL_NAMESPACE' => $this->getModelNamespace($module, $this->getModelName()),
            'FIELDS' => $this->getFields(),
        ]))->render();
    }

    /**
     * @return string
     */
    protected function getDestinationFilePath(): string
    {
        $path = app(RepositoryInterface::class)->getModulePath($this->getModuleName());

        $resourcePath = GenerateConfigReader::read('resource');

        return $path.$resourcePath->getPath().'/'.$this->getFileName().'.php';
    }

    /**
     * @return string
     */
    protected function getFileName(): string
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Determine if the command is generating a resource collection.
     *
     * @return bool
     */
    protected function collection(): bool
    {
        return $this->option('collection') ||
            Str::endsWith($this->argument('name'), 'Collection');
    }

    /**
     * @return string
     */
    protected function getStubName(): string
    {
        if ($this->collection()) {
            if ($this->withModel) {
                return '/resources/resource-collection-with-model.stub';
            }

            return '/resources/resource-collection.stub';
        }

        if ($this->withModel) {
            return '/resources/resource-with-model.stub';
        }

        return '/resources/resource.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the resource class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ...$this->fromModelOptions,
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the resource already exists.'],
            ['collection', 'c', InputOption::VALUE_NONE, 'Create a resource collection.'],
        ];
    }
}
