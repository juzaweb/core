<?php

namespace Juzaweb\Core\Modules\Commands;

use Illuminate\Support\Str;
use Juzaweb\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Core\Modules\Support\Stub;
use Juzaweb\Core\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class RepositoryEloquentMakeCommand extends GeneratorCommand
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
    protected $name = 'module:make-eloquent-repository';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new restful eloquent repository for the specified module.';

    /**
     * Get repository name.
     *
     * @return string
     */
    public function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $repositoryPath = GenerateConfigReader::read('repository');

        return $path . $repositoryPath->getPath() . '/' . $this->getRepositoryEloquentName() . '.php';
    }

    /**
     * @return string
     */
    protected function getTemplateContents()
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'MODULENAME'        => $module->getStudlyName(),
            'REPOSITORY_ELOQUENT_NAME'    => $this->getRepositoryEloquentName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module),
            'CLASS'             => $this->getRepositoryEloquentNameWithoutNamespace(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => $this->laravel['modules']->config('namespace'),
            'MODEL_CLASS'       => $this->getModelClass($module),
            'MODEL_NAMESPACE'   => $this->getModelNamepace($module),
            'REPOSITORY_NAMESPACE' => $this->getRepositoryNamepace($module),
            'REPOSITORY_NAME'   => $this->getRepositoryName($module),
        ]))->render();
    }

    public function getRepositoryName($module): string
    {
        return Str::replace('Eloquent', '', $this->getRepositoryEloquentNameWithoutNamespace());
    }

    protected function getRepositoryNamepace($module): string
    {
        $class = Str::replace('Eloquent', '', $this->getRepositoryEloquentNameWithoutNamespace());

        $moduleNamespace = str_replace('\\Repositories', '', $this->getClassNamespace($module));

        return $moduleNamespace . '\\'
            . $this->laravel['modules']->config('paths.generator.repository.namespace', 'Repositories')
            . '\\' . $class;
    }

    protected function getModelNamepace($module): string
    {
        $class = $this->getModelClass($module);

        $moduleNamespace = str_replace('\\Repositories', '', $this->getClassNamespace($module));

        return $moduleNamespace . '\\'
            . $this->laravel['modules']->config('paths.generator.model.namespace', 'Models')
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
        return str_replace('RepositoryEloquent', '', $this->getRepositoryEloquentNameWithoutNamespace());
    }

    /**
     * @return array|string
     */
    protected function getRepositoryEloquentName()
    {
        $repository = Str::studly($this->argument('repository'));

        if (Str::contains(strtolower($repository), 'repositoryeloquent') === false) {
            $repository .= 'RepositoryEloquent';
        }

        return $repository;
    }

    /**
     * @return array|string
     */
    private function getRepositoryEloquentNameWithoutNamespace()
    {
        return class_basename($this->getRepositoryEloquentName());
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
        return '/repositories/eloquent-repository.stub';
    }
}
