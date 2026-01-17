<?php

namespace Juzaweb\Modules\Core\Modules\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Modules\Exceptions\FileAlreadyExistException;
use Juzaweb\Modules\Core\Modules\Generators\FileGenerator;
use Juzaweb\Modules\Core\Modules\Module;

abstract class GeneratorCommand extends Command
{
    /**
     * The name of 'name' argument.
     *
     * @var string
     */
    protected string $argumentName = '';

    /**
     * Get template contents.
     *
     * @return string
     */
    abstract protected function getTemplateContents();

    /**
     * Get the destination file path.
     *
     * @return string
     */
    abstract protected function getDestinationFilePath();

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (method_exists($this, 'preGenerate')) {
            $preGenerate = $this->preGenerate();

            if ($preGenerate === false) {
                return E_ERROR;
            }
        }

        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (!$this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        try {
            $this->components->task("Generating file {$path}",function () use ($path,$contents) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();
            });

            if (method_exists($this, 'postGenerate')) {
                $this->postGenerate();
            }

        } catch (FileAlreadyExistException $e) {
            $this->components->error("File : {$path} already exists.");

            return E_ERROR;
        }

        return 0;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClass()
    {
        return class_basename($this->argument($this->argumentName));
    }

    /**
     * Get default namespace.
     *
     * @return string
     */
    public function getDefaultNamespace(): string
    {
        return '';
    }

    /**
     * Get class namespace.
     *
     * @param  Module  $module
     * @param  string|null  $defaultNamespace
     * @param  string|null  $extra
     * @return string
     */
    public function getClassNamespace(Module $module, ?string $defaultNamespace = null, ?string $extra = null): string
    {
        $namespace = $this->laravel['modules']->config('namespace');

        $namespace .= '\\' . $module->getStudlyName();

        $namespace .= '\\' . ($defaultNamespace ?? $this->getDefaultNamespace());

        if (! $extra) {
            $extra = str_replace($this->getClass(), '', $this->argument($this->argumentName));

            $extra = str_replace('/', '\\', $extra);
        }

        $namespace .= '\\' . $extra;

        $namespace = str_replace('/', '\\', $namespace);

        return trim($namespace, '\\');
    }
}
