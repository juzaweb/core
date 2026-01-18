<?php

namespace Juzaweb\Modules\Core\Modules\Commands\Cruds;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Models\Model;
use Juzaweb\Modules\Core\Modules\Exceptions\FileAlreadyExistException;
use Juzaweb\Modules\Core\Modules\Generators\FileGenerator;
use Juzaweb\Modules\Core\Modules\Module;
use Juzaweb\Modules\Core\Modules\Support\Config\GenerateConfigReader;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Juzaweb\Modules\Core\Modules\Traits\UseFromModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class AdminCrudMakeCommand extends Command
{
    use UseFromModel;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'module:make-admin-crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make admin crud for the specified module.';

    protected string $argumentName = 'model';

    protected string $modelName;

    protected string $tableName;

    public function handle(): int
    {
        $this->modelName = class_basename($this->argument('model'));

        $this->generateDataTable();

        $this->generateController();

        $this->generateRequests();

        $this->generateViews();

        $module = \Juzaweb\Modules\Core\Facades\Module::findOrFail($this->getModuleName());
        $routePath = GenerateConfigReader::read('routes');
        $adminRouteFile = $this->getModulePath() . $routePath->getPath() . '/admin.php';
        $controllerClass = $this->getClassNamespace($module, 'Http\\Controllers\\')
            . "\\" . $this->getControllerNameWithoutNamespace();

        $content = file_get_contents($adminRouteFile);

        // Tìm tất cả các dòng use hiện có
        if (preg_match_all('/^use .*;$/m', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $lastUse = end($matches[0]);
            $pos = $lastUse[1] + strlen($lastUse[0]);

            // Chèn use mới ngay sau use cuối
            $content = substr_replace($content, "\nuse {$controllerClass};", $pos, 0);
        } else {
            // Nếu không có use nào, thêm ở đầu file
            $content = "use {$controllerClass};\n\n" . $content;
        }

        // Thêm Route::admin(...) xuống cuối file
        $routeLine = "\nRoute::admin('{$this->getUrlPrefix()}', " . $this->getControllerNameWithoutNamespace() . "::class);\n";
        $content = rtrim($content) . $routeLine;

        file_put_contents($adminRouteFile, $content);

        $this->info("Admin CRUD generated successfully for model {$this->modelName}.");

        return static::SUCCESS;
    }

    protected function generateDataTable(): void
    {
        $this->call('module:make-datatable', [
            'datatable' => $this->argument('model'),
            'module' => $this->argument('module'),
            '--force' => $this->option('force'),
            '--for-model' => $this->getModelName(),
        ]);
    }

    protected function generateController(): int
    {
        $path = $this->getModulePath();

        $controllerPath = GenerateConfigReader::read('controller');

        $path .= $controllerPath->getPath().'/'.$this->getControllerName().'.php';

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $module = \Juzaweb\Modules\Core\Facades\Module::findOrFail($this->getModuleName());

        $contents = (new Stub('cruds/admin/controller.stub', [
            ...$this->getBaseReplaceVariables($module),
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

    protected function generateViews(): int
    {
        $path = $this->getModulePath();

        $viewPath = GenerateConfigReader::read('views');

        $lowerSingularTitle = Str::slug(Str::singular($this->getTitle()));

        $path .= $viewPath->getPath()."/{$lowerSingularTitle}/index.blade.php";

        if (!File::isDirectory($dir = dirname($path))) {
            File::makeDirectory($dir, 0777, true);
        }

        $module = \Juzaweb\Modules\Core\Facades\Module::findOrFail($this->getModuleName());

        $contents = (new Stub('cruds/admin/resources/views/index.stub', [
            ...$this->getBaseReplaceVariables($module),
        ]))->render();

        try {
            $this->components->task("Generating file {$path}",function () use ($path, $contents) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();
            });

        } catch (FileAlreadyExistException $e) {
            $this->components->error("File: {$path} already exists.");
        }

        $path = $this->getModulePath();
        $path .= $viewPath->getPath()."/{$lowerSingularTitle}/form.blade.php";

        $model = app($this->getModelClass($module));
        $contents = (new Stub('cruds/admin/resources/views/form.stub', [
            ...$this->getBaseReplaceVariables($module),
            'FIELDS' => implode("\n\n\t\t\t\t\t", $this->mapFieldsFromModel($model)),
        ]))->render();

        try {
            $this->components->task("Generating file {$path}",function () use ($path, $contents) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();
            });

        } catch (FileAlreadyExistException $e) {
            $this->components->error("File: {$path} already exists.");
        }

        return static::SUCCESS;
    }

    protected function getBaseReplaceVariables(Module $module): array
    {
        return [
            'MODULENAME' => $module->getStudlyName(),
            'CONTROLLERNAME' => $this->getControllerName(),
            'NAMESPACE' => $module->getStudlyName(),
            'CLASS_NAMESPACE' => $this->getClassNamespace($module, 'Http\\Controllers\\'),
            'CLASS' => $this->getControllerNameWithoutNamespace(),
            'LOWER_NAME' => $module->getLowerName(),
            'MODULE' => $this->getModuleName(),
            'NAME' => $this->getModuleName(),
            'STUDLY_NAME' => $module->getStudlyName(),
            'MODULE_NAMESPACE' => \Juzaweb\Modules\Core\Facades\Module::config('namespace'),
            'MODEL_NAMESPACE' => $this->getModelClass($module),
            'URL_PREFIX' => $this->getUrlPrefix(),
            'MODEL_CLASS' => $this->getModelName(),
            'DATATABLE_NAMESPACE' => $this->getDatatableNamespace($module),
            'DATATABLE_CLASS' => class_basename($this->getDatatableNamespace($module)),
            'TITLE' => $this->getTitle(),
            'REQUEST_NAMESPACE' => $this->getRequestNamespace($module),
            'BULK_REQUEST_NAMESPACE' => $this->getBulkRequestNamespace($module),
            'SINGULAR_TITLE' => Str::singular($this->getTitle()),
            'PLURAL_TITLE' => Str::plural($this->getTitle()),
            'LOWER_SINGULAR_TITLE' => Str::lower(Str::singular($this->getTitle())),
            'RESOURCE_NAMESPCAE' => $module->getResourceNamespace(),
            'KEBAB_SINGULAR_TITLE' => Str::singular(Str::kebab($this->getTitle())),
        ];
    }

    protected function mapFieldsFromModel(Model $model): array
    {
        $makeColumns = $this->getAllModelColumns($model);

        $makeColumns = array_diff($makeColumns, ['created_at', 'updated_at', 'deleted_at', 'deleted_at']);

        $fields = [];
        foreach ($makeColumns as $item) {
            $label = title_from_key($item);

            if ($item === 'active') {
                $fields[] = "{{ Field::checkbox(__('". $label ."'), '{$item}', ['value' => \$model->{$item}]) }}";
                continue;
            }

            if ($item === 'status') {
                $fields[] = "{{ Field::select(__('". $label ."'), '{$item}')->dropDownList([]) }}";
                continue;
            }

            $fields[] = "{{ Field::text(__('". $label ."'), '{$item}', ['value' => \$model->{$item}]) }}";
        }

        return $fields;
    }

    protected function getTitle(): string
    {
        return Str::plural(Str::title(Str::snake($this->argument('model'), ' ')));
    }

    protected function getUrlPrefix(): string
    {
        return Str::plural(Str::slug($this->argument('model')));
    }

    protected function getDatatableNamespace(Module $module): string
    {
        return $this->getClassNamespace($module, 'Http\\DataTables', Str::plural($this->getModelName()) . "DataTable");
    }

    protected function getFormNamespace(Module $module): string
    {
        return $this->getClassNamespace($module, 'Http\\Forms', "{$this->getModelName()}Form");
    }

    /**
     * @return string
     */
    protected function getControllerNameWithoutNamespace(): string
    {
        return class_basename($this->getControllerName());
    }

    protected function getRequestNamespace(Module $module): string
    {
        return $this->getClassNamespace($module, 'Http\\Requests', "{$this->modelName}Request");
    }

    protected function getBulkRequestNamespace(Module $module): string
    {
        return $this->getClassNamespace($module, 'Http\\Requests', "{$this->modelName}ActionsRequest");
    }

    protected function getControllerName(): string
    {
        $controller = Str::studly($this->argument('model'));

        if (Str::contains(strtolower($controller), 'controller') === false) {
            $controller .= 'Controller';
        }

        return $controller;
    }

    protected function getModulePath(): string
    {
        return \Juzaweb\Modules\Core\Facades\Module::getModulePath($this->getModuleName());
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

    protected function getModelName(): string
    {
        return $this->option('for-model') ?? $this->modelName;
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
