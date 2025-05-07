<?php

namespace Juzaweb\Core\Modules\Commands;

use Illuminate\Support\Str;
use Juzaweb\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Core\Modules\Support\Stub;
use Juzaweb\Core\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RepositoryMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    /**
     * The name of argument being used.
     *
     * @var string
     */
    protected string $argumentName = 'repository';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'module:make-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful repository for the specified module.';

    /**
     * Get repository name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $repositoryPath = GenerateConfigReader::read('repository');

        return $path . $repositoryPath->getPath() . '/' . $this->getRepositoryName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getRepositoryName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getRepositoryNameWithoutNamespace(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
            'MODEL_CLASS'       => $this->getModelClass($module),
            'MODEL_NAMESPACE'   => $this->getModelNamepace($module),
        ]))->render();
    }

    public function postGenerate(): void
    {
        if (!$this->option('no-eloquent')) {
            $this->call('module:make-eloquent-repository', [
                'repository' => $this->argument('repository'),
                'module' => $this->getModuleName(),
            ]);

            $module = $this->laravel['modules']->findOrFail($this->getModuleName());

            $namespace = $this->getClassNamespace($module);
            $class = $this->getRepositoryNameWithoutNamespace();

            $this->warn("Add Eloquent repository {$this->argument('repository')} to you respositories config");
            $this->warn("\\{$namespace}\\{$class}::class => \\{$namespace}\\{$class}Eloquent::class");
        }
    }

    protected function getModelNamepace($module): string
    {
        $class = $this->getModelClass($module);

        $moduleNamespace = str_replace('\\Repositories', '', $this->getClassNamespace($module));

        return $moduleNamespace . '\\'
            . $this->laravel['modules']->config('paths.generator.model.namespace', 'App\Models')
            . '\\' . $class;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['repository', InputArgument::REQUIRED, 'The name of the repository class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getModelClass($module): string
    {
        return str_replace('Repository', '', $this->getRepositoryNameWithoutNamespace());
    }

    /**
     * @return array|string
     */
    protected function getRepositoryName()
    {
        $repository = Str::studly($this->argument('repository'));

        if (Str::contains(strtolower($repository), 'repository') === false) {
            $repository .= 'Repository';
        }

        return $repository;
    }

    /**
     * @return array|string
     */
    private function getRepositoryNameWithoutNamespace()
    {
        return class_basename($this->getRepositoryName());
    }

    public function getDefaultNamespace(): string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.repository.namespace') ?: $module->config('paths.generator.repository.path', 'Repositories');
    }

    /**
     * Get the stub file name based on the options
     * @return string
     */
    protected function getStubName()
    {
        return '/repositories/repository.stub';
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['no-eloquent', null, InputOption::VALUE_NONE, 'Generate Eloquent repository'],
        ];
    }
}
