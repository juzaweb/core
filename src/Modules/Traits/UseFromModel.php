<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Modules\Traits;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Juzaweb\Core\Models\Model;
use Juzaweb\Core\Modules\Module;
use Juzaweb\Translations\Contracts\Translatable;
use Symfony\Component\Console\Input\InputOption;

trait UseFromModel
{
    use ModuleCommandTrait;

    protected array $fromModelOptions = [
        ['for-model', 'm', InputOption::VALUE_OPTIONAL, 'Create the class for model.'],
    ];

    protected function makeModel(Module $module): bool|Model
    {
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

        return $model;
    }

    protected function getAllModelColumns(Model $model): array
    {
        $columns = $this->getTranslatedColumns($model);

        if ($fillable = $model->getFillable()) {
            $columns = [
                ...$columns,
                ...$fillable,
            ];
        }

        return $columns;
    }

    protected function getTranslatedColumns(Model $model): array
    {
        $columns = [];
        if ($model instanceof Translatable) {
            $columns = $model->getTranslatedFields();
        }

        return $columns;
    }

    protected function getModelNamespace(Module $module, ?string $model = null): string
    {
        $base = $this->getClassNamespace($module, 'Models');

        return $model ? $base."\\{$model}" : $base;
    }

    protected function getModelName(): string
    {
        return $this->option('for-model') ?? Str::studly(str_replace(
            $this->argumentSubfix ?? '',
            '',
            $this->argument($this->argumentName))
        );
    }

    protected function getModelClass(Module $module): string
    {
        return $this->getModelNamespace($module)."\\{$this->getModelName()}";
    }

    protected function getRepositoryNamespace(Module $module): string
    {
        return $this->getClassNamespace($module, 'Repositories');
    }

    protected function getRepositoryName(): string
    {
        $argumentName = $this->argument($this->argumentName);

        if (isset($this->argumentSubfix)) {
            $argumentName = str_replace($this->argumentSubfix, '', $argumentName);
        }

        return ($this->option('for-model') ?? Str::studly($argumentName)).'Repository';
    }

    protected function getTableName(Module $module): string
    {
        return $this->makeModel($module)->getTable();
    }

    protected function getRepositoryClass(Module $module): string
    {
        return $this->getRepositoryNamespace($module)."\\{$this->getRepositoryName()}";
    }
}
