<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Modules\Process\Installer;
use Juzaweb\Modules\Core\Modules\Support\Json;
use Juzaweb\Modules\Core\Themes\Support\ThemeRepositoryAdapter;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ThemeInstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the specified theme by given package name (vendor/name).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (is_null($this->argument('name'))) {
            return $this->installFromFile();
        }

        $this->install(
            $this->argument('name'),
            $this->argument('version'),
            $this->option('type'),
            $this->option('tree')
        );

        return 0;
    }

    /**
     * Install themes from themes.json file.
     */
    protected function installFromFile(): int
    {
        if (!file_exists($path = base_path('composer.json'))) {
            $this->error("File 'themes.json' does not exist in your project root.");

            return E_ERROR;
        }

        $themes = Json::make($path);

        $dependencies = $themes->get('require', []);

        foreach ($dependencies as $theme) {
            $theme = collect($theme);

            $this->install(
                $theme->get('name'),
                $theme->get('version'),
                $theme->get('type')
            );
        }

        return 0;
    }

    /**
     * Install the specified theme.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $tree
     */
    protected function install($name, $version = 'dev-master', $type = 'composer', $tree = false)
    {
        // Create adapter to make ThemeRepository compatible with Installer
        $adapter = new ThemeRepositoryAdapter(
            $this->laravel['themes'],
            $this->laravel['files']
        );

        $installer = new Installer(
            $name,
            $version,
            $type ?: $this->option('type'),
            $tree ?: $this->option('tree')
        );

        $installer->setRepository($adapter);

        $installer->setConsole($this);

        if ($timeout = $this->option('timeout')) {
            $installer->setTimeout($timeout);
        }

        if ($path = $this->option('path')) {
            $installer->setPath($path);
        }

        $installer->run();

        if (!$this->option('no-update')) {
            $this->call('theme:update', [
                'theme' => $installer->getModuleName(),
            ]);
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::OPTIONAL, 'The name of theme will be installed.'],
            ['version', InputArgument::OPTIONAL, 'The version of theme will be installed.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['timeout', null, InputOption::VALUE_OPTIONAL, 'The process timeout.', null],
            ['path', null, InputOption::VALUE_OPTIONAL, 'The installation path.', null],
            ['type', null, InputOption::VALUE_OPTIONAL, 'The type of installation.', null],
            ['tree', null, InputOption::VALUE_NONE, 'Install the theme as a git subtree', null],
            ['no-update', null, InputOption::VALUE_NONE, 'Disables the automatic update of the dependencies.', null],
        ];
    }
}
