<?php

use Illuminate\Foundation\Vite;
use Illuminate\Support\Facades\Vite as ViteFacade;

if (! function_exists('module_path')) {
    /**
     * Get the path for a specific module.
     *
     * @param string $name The name of the module
     * @param string $path Optional additional path to append
     * @return string The complete path to the module or the specified sub-path
     */
    function module_path($name, $path = ''): string
    {
        $module = app('modules')->find($name);

        return $module->getPath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path(string $path = ''): string
    {
        return app()->basePath() . '/config' . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (! function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param string $path
     * @return string
     */
    function public_path(string $path = ''): string
    {
        return app()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}

if (! function_exists('module_vite')) {
    /**
     * support for vite
     */
    function module_vite($module, $asset): Vite
    {
        return ViteFacade::useHotFile(storage_path('vite.hot'))->useBuildDirectory($module)->withEntryPoints([$asset]);
    }
}
