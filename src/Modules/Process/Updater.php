<?php

namespace Juzaweb\Modules\Core\Modules\Process;

use Juzaweb\Modules\Core\Modules\Module;
use Juzaweb\Modules\Core\Themes\Theme;

class Updater extends Runner
{
    /**
     * Update the dependencies for the specified module by given the module name.
     *
     * @param  string  $module
     */
    public function update(string $module)
    {
        $module = $this->module->findOrFail($module);

        chdir(base_path());

        $this->installRequires($module);
        // $this->installDevRequires($module);
        // $this->copyScriptsToMainComposerJson($module);
    }

    /**
     * Check if composer should output anything.
     *
     * @return string
     */
    private function isComposerSilenced()
    {
        // return config('modules.composer.composer-output') === false ? ' --quiet' : '';
        return ' --quiet';
    }

    /**
     * @param  Module|Theme  $module
     */
    private function installRequires(Module|Theme $module)
    {
        $packages = $module->getComposerAttr('require', []);

        $concatenatedPackages = '';
        foreach ($packages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        $phpPath = get_php_binary_path();

        if (!empty($concatenatedPackages)) {
            $this->run("{$phpPath} composer.phar require {$concatenatedPackages}{$this->isComposerSilenced()}");
        }
    }

    /**
     * @param Module|Theme $module
     */
    private function installDevRequires(Module|Theme $module)
    {
        $devPackages = $module->getComposerAttr('require-dev', []);
        $phpPath = get_php_binary_path();

        $concatenatedPackages = '';
        foreach ($devPackages as $name => $version) {
            $concatenatedPackages .= "\"{$name}:{$version}\" ";
        }

        if (!empty($concatenatedPackages)) {
            $this->run("{$phpPath} composer.phar require --dev {$concatenatedPackages}{$this->isComposerSilenced()}");
        }
    }

    /**
     * @param Module|Theme $module
     */
    private function copyScriptsToMainComposerJson(Module|Theme $module)
    {
        $scripts = $module->getComposerAttr('scripts', []);

        $composer = json_decode(
            file_get_contents(base_path('composer.json')),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        foreach ($scripts as $key => $script) {
            if (array_key_exists($key, $composer['scripts'])) {
                $composer['scripts'][$key] = array_unique(array_merge($composer['scripts'][$key], $script));

                continue;
            }

            $composer['scripts'] = array_merge($composer['scripts'], [$key => $script]);
        }

        file_put_contents(base_path('composer.json'),
            json_encode($composer, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
}
