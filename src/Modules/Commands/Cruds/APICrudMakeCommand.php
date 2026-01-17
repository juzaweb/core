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
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Modules\Contracts\RepositoryInterface;
use Juzaweb\Modules\Core\Modules\Exceptions\FileAlreadyExistException;
use Juzaweb\Modules\Core\Modules\Generators\FileGenerator;
use Juzaweb\Modules\Core\Modules\Module;
use Juzaweb\Modules\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Juzaweb\Modules\Core\Modules\Traits\UseFromModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class APICrudMakeCommand extends Command
{
    use UseFromModel;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-api-crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make api crud for the specified module.';

    protected string $argumentName = 'model';

    protected string $modelName;

    protected string $tableName;

    public function handle(): int
    {
        $this->modelName = class_basename($this->argument('model'));

        $this->generateRequests();

        $this->generateController();

        $this->info("API CRUD generated successfully for model {$this->modelName}.");

        return static::SUCCESS;
    }

    protected function generateRequests(): void
    {
        $this->call('module:make-request', [
            'name' => "{$this->modelName}Request",
            'module' => $this->getModuleName(),
            '--force' => $this->option('force'),
            '--for-model' => $this->modelName,
        ]);

        $this->call('module:make-request', [
            'name' => "{$this->modelName}ActionsRequest",
            'module' => $this->getModuleName(),
            '--force' => $this->option('force'),
            '--for-model' => $this->modelName,
            '--bulk' => true,
        ]);
    }

    protected function generateController(): int
    {
        $path = $this->getModulePath();

        $controllerPath = GenerateConfigReader::read('controller');

        $path .= $controllerPath->getPath().'/APIs/'.$this->getControllerName().'.php';

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $module = app(RepositoryInterface::class)->findOrFail($this->getModuleName());

        $this->tableName = $this->makeModel($module)->getTable();

        $contents = (new Stub('cruds/api/controller.stub', [
            'MODULENAME'        => $module->getStudlyName(),
            'CONTROLLERNAME'    => $this->getControllerName(),
            'NAMESPACE'         => $module->getStudlyName(),
            'CLASS_NAMESPACE'   => $this->getClassNamespace($module, 'Http\\Controllers\\APIs\\'),
            'CLASS'             => $this->getControllerNameWithoutNamespace(),
            'LOWER_NAME'        => $module->getLowerName(),
            'MODULE'            => $this->getModuleName(),
            'NAME'              => $this->getModuleName(),
            'STUDLY_NAME'       => $module->getStudlyName(),
            'MODULE_NAMESPACE'  => app(RepositoryInterface::class)->config('namespace'),
            'MODEL_NAMESPACE'   => $this->getModelClass($module),
            'URL_PREFIX'        => $this->getUrlPrefix(),
            'MODEL_NAME'       => $this->getModelName(),
            'REPOSITORY_NAMESPACE' => $this->getRepositoryClass($module),
            'REPOSITORY_CLASS'   => $this->getRepositoryName(),
            'REPOSITORY_NAME'    => Str::camel($this->getRepositoryName()),
            'TITLE'             => $this->getTitle(),
            'SINGULAR_TITLE'    => Str::singular($this->getTitle()),
            'TABLE'             => $this->tableName,
            'REQUEST_NAMESPACE' => $this->getRequestNamespace($module),
            'BULK_REQUEST_NAMESPACE' => $this->getBulkRequestNamespace($module),
            'REQUEST_NAME'     => "{$this->modelName}Request",
            'BULK_REQUEST_NAME' => "{$this->modelName}ActionsRequest",
        ]))->render();

        try {
            $this->components->task("Generating file {$path}",function () use ($path, $contents) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();
            });

        } catch (FileAlreadyExistException $e) {
            $this->components->error("File: {$path} already exists.");

            return E_ERROR;
        }

        return static::SUCCESS;
    }

    protected function getRequestNamespace(Module $module): string
    {
        return $this->getClassNamespace($module, 'Http\\Requests', "{$this->modelName}Request");
    }

    protected function getBulkRequestNamespace(Module $module): string
    {
        return $this->getClassNamespace($module, 'Http\\Requests', "{$this->modelName}ActionsRequest");
    }

    protected function getTitle(): string
    {
        return Str::plural(Str::title(Str::snake($this->argument('model'), ' ')));
    }

    protected function getUrlPrefix(): string
    {
        return Str::plural(Str::slug($this->argument('model')));
    }

    /**
     * @return string
     */
    protected function getControllerNameWithoutNamespace(): string
    {
        return class_basename($this->getControllerName());
    }

    protected function getControllerName(): string
    {
        $controller = Str::studly($this->argument('model'));

        if (Str::contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    protected function getModulePath()
    {
        return app(RepositoryInterface::class)->getModulePath($this->getModuleName());
    }

    /**
     * Get class name.
     *
     * @return string
     */
    protected function getClass(): string
    {
        return $this->modelName;
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
        ];
    }
}
