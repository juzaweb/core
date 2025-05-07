<?php

namespace Juzaweb\Core\Modules\Commands;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Core\Modules\Support\Config\GeneratorPath;
use Juzaweb\Core\Modules\Support\Stub;
use Juzaweb\Core\Modules\Traits\UseFromModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class FormMakeCommand extends GeneratorCommand
{
    use UseFromModel;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-form';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new form.';

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected string $argumentName = 'form';

    protected array $fields = [];

    /**
     * Sidebar fields
     *
     * @var array
     */
    protected array $sidebarFields = [];

    protected bool $hasSidebar = false;

    protected function preGenerate(): bool
    {
        $module = app(RepositoryInterface::class)->findOrFail($this->getModuleName());

        $model = $this->makeModel($module);

        if ($model === false) {
            return false;
        }

        if ($this->option('sidebar')) {
            $this->hasSidebar = true;
        }

        $config = GenerateConfigReader::read('form');

        $this->mapFieldsFromModel($model, $config);

        return true;
    }

    protected function mapFieldsFromModel(Model $model, GeneratorPath $config): void
    {
        $makeColumns = $this->getAllModelColumns($model);

        $makeColumns = array_diff($makeColumns, $config->get('excludeColumns', []));

        $this->generateFields(array_diff($makeColumns, $config->get('sidebarColumns', [])), 'fields', $config);

        $this->generateFields(Arr::only($makeColumns, $config->get('sidebarColumns', [])), 'sidebarFields', $config);
    }

    protected function generateFields(array $columns, string $property, GeneratorPath $config): void
    {
        $checkboxColumns = $config->get('checkboxColumns', []);

        foreach ($columns as $column) {
            if (in_array($column, $checkboxColumns)) {
                $this->{$property}[] = "Field::checkbox('{$column}')->value(\$this->model?->{$column})";
                continue;
            }

            $this->{$property}[] = "Field::text('{$column}')->value(\$this->model?->{$column})";
        }
    }

    protected function getTemplateContents(): string
    {
        $module = app(RepositoryInterface::class)->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME' => $module->getStudlyName(),
            'NAMESPACE' => $module->getStudlyName(),
            'CLASS_NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getFormNameWithoutNamespace(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getModuleName(),
            'STUDLY_NAME' => $module->getStudlyName(),
            'MODULE_NAMESPACE' => app(RepositoryInterface::class)->config('namespace'),
            'URL_PREFIX' => $this->getUrlPrefix(),
            'MODEL_NAMESPACE' => $this->getModelClass($module),
            'MODEL_CLASS' => $this->getModelName(),
            'FIELDS' => $this->getFields(),
            'SIDEBAR_FIELDS' => $this->getSidebarFields(),
        ]))->render();
    }

    protected function getFields(): string
    {
        return "[\n\t\t\t\t\t".implode(",\n\t\t\t\t\t", $this->fields)."\n\t\t\t\t]";
    }

    protected function getSidebarFields(): string
    {
        return "[\n\t\t\t\t\t".implode(",\n\t\t\t\t\t", $this->sidebarFields)."\n\t\t\t\t]";
    }

    protected function getUrlPrefix(): string
    {
        return Str::plural(Str::slug($this->argument('form')));
    }

    protected function getDestinationFilePath(): string
    {
        $path = app(RepositoryInterface::class)->getModulePath($this->getModuleName());

        $formPath = GenerateConfigReader::read('form');

        return $path.$formPath->getPath().'/'.$this->getFormName().'.php';
    }

    protected function getFormName(): string
    {
        $form = Str::studly($this->argument('form'));

        if (Str::contains(strtolower($form), 'form') === false) {
            $form .= 'Form';
        }

        return $form;
    }

    /**
     * @return string
     */
    protected function getFormNameWithoutNamespace(): string
    {
        return class_basename($this->getFormName());
    }

    /**
     * Get the stub file name based on the options
     * @return string
     */
    protected function getStubName(): string
    {
        if ($this->hasSidebar) {
            return '/forms/form-with-sidebar.stub';
        }

        return '/forms/form.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        return GenerateConfigReader::read('form')->get('namespace', 'Http/Forms');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['form', InputArgument::REQUIRED, 'The name of the form class.'],
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
            ['sidebar', null, InputOption::VALUE_NONE, 'Generate form with sidebar.'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the module is not installed.'],
        ];
    }
}
