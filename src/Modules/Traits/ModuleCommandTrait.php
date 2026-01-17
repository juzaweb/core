<?php

namespace Juzaweb\Modules\Core\Modules\Traits;

use Juzaweb\Modules\Core\Modules\Module;

trait ModuleCommandTrait
{
    /**
     * Get the module name.
     *
     * @return string
     */
    public function getModuleName(): string
    {
        $module = $this->argument('module') ?: app('modules')->getUsedNow();

        $module = app('modules')->findOrFail($module);

        return $module->getStudlyName();
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
