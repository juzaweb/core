<?php

namespace Juzaweb\Modules\Core\Modules\Commands;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\Module;
use Juzaweb\Modules\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Modules\Core\Modules\Support\Config\GeneratorPath;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Juzaweb\Modules\Core\Modules\Traits\UseFromModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DatatableMakeCommand extends GeneratorCommand
{
    use UseFromModel;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-datatable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new datatable.';

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected string $argumentName = 'datatable';

    protected array $columns = [];

    protected array $actions = [];

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

        $config = GenerateConfigReader::read('datatable');

        $this->mapColumnsFromModel($model, $config);

        // $this->mapActionsFromRepository($module, $config);

        return true;
    }

    protected function mapActionsFromRepository(Module $module, GeneratorPath $config): void
    {
        try {
            /** @var Model $model */
            $repository = app($this->getRepositoryClass($module));
        } catch (BindingResolutionException $e) {
            return;
        }

        if (!method_exists($repository, 'bulkActions')) {
            return;
        }

        $actions = array_diff($repository->bulkActions(), $config->get('excludeActions', []));

        foreach ($actions as $action) {
            $this->actions[] = "BulkAction::make('{$action}')->icon('{$action}')";
        }
    }

    protected function mapColumnsFromModel(Model $model, GeneratorPath $config): void
    {
        $makeColumns = $this->getAllModelColumns($model);

        $makeColumns = array_diff($makeColumns, $config->get('excludeColumns', []));

        $this->columns[] = "Column::checkbox()";
        $this->columns[] = "Column::id()";
        $this->columns[] = "Column::actions()";

        $hasTitle = false;
        foreach ($makeColumns as $item) {
            if (! $hasTitle && in_array($item, $config->get('titleColumns'))) {
                $label = Str::title(str_replace('_', ' ', $item));
                $prefix = $this->getUrlPrefix();
                $this->columns[] = "Column::editLink('{$item}', admin_url('{$prefix}/{id}/edit'), __('admin::translation.label'))";
                $hasTitle = true;
                continue;
            }

            $this->columns[] = "Column::make('{$item}')";
        }

        if ($model->usesTimestamps()) {
            $this->columns[] = "Column::createdAt()";
        }
    }

    protected function getTemplateContents(): string
    {
        $module = \Juzaweb\Modules\Core\Facades\Module::findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME' => $module->getStudlyName(),
            'NAMESPACE' => $module->getStudlyName(),
            'CLASS_NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getDatatableNameWithoutNamespace(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getModuleName(),
            'STUDLY_NAME' => $module->getStudlyName(),
            'MODULE_NAMESPACE' => \Juzaweb\Modules\Core\Facades\Module::config('namespace'),
            'URL_PREFIX' => $this->getUrlPrefix(),
            'COLUMNS' => $this->getColumns(),
            'BULK_ACTIONS' => $this->getBulkActions(),
            'MODEL_NAME' => $this->getModelName(),
        ]))->render();
    }

    protected function getBulkActions(): string
    {
        return "[\n\t\t\t".implode(",\n\t\t\t", $this->actions)."\n\t\t]";
    }

    protected function getColumns(): string
    {
        return "[\n\t\t\t".implode(",\n\t\t\t", $this->columns)."\n\t\t]";
    }

    protected function getUrlPrefix(): string
    {
        $datatable = Str::snake($this->argument('datatable'));

        $datatable = str_replace('_', ' ', $datatable);

        return Str::plural(Str::slug($datatable));
    }

    protected function getDestinationFilePath(): string
    {
        $path = \Juzaweb\Modules\Core\Facades\Module::getModulePath($this->getModuleName());

        $datatablePath = GenerateConfigReader::read('datatable');

        return $path . $datatablePath->getPath() . '/' . $this->getDatatableName() . '.php';
    }

    protected function getDatatableName(): string
    {
        $datatable = Str::plural(Str::studly($this->argument('datatable')));

        if (Str::contains(strtolower($datatable), 'datatable') === false) {
            $datatable .= 'DataTable';
        }

        return $datatable;
    }

    public function getDefaultNamespace(): string
    {
        return \Juzaweb\Modules\Core\Facades\Module::config('paths.generator.datatable.namespace', 'Http/DataTables');
    }

    /**
     * @return string
     */
    protected function getDatatableNameWithoutNamespace(): string
    {
        return class_basename($this->getDatatableName());
    }

    /**
     * Get the stub file name based on the options
     * @return string
     */
    protected function getStubName(): string
    {
        return '/datatables/datatable.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['datatable', InputArgument::REQUIRED, 'The name of the datatable class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): ?array
    {
        return [
            ...$this->fromModelOptions,
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the module is not installed.'],
        ];
    }
}
