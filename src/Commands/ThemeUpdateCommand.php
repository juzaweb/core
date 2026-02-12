<?php

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Contracts\Theme as ThemeContract;
use Juzaweb\Modules\Core\Modules\Process\Updater;
use Juzaweb\Modules\Core\Themes\Support\ThemeRepositoryAdapter;
use Juzaweb\Modules\Core\Themes\Theme;
use Symfony\Component\Console\Input\InputArgument;

class ThemeUpdateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'theme:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update dependencies for the specified theme or for all themes.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->components->info('Updating theme ...');

        if ($name = $this->argument('theme')) {
            $this->updateTheme($name);

            return 0;
        }

        $this->updateAllTheme();

        return 0;
    }

    protected function updateAllTheme()
    {
        $themes = $this->laravel[ThemeContract::class]->all();

        foreach ($themes as $theme) {
            $this->updateTheme($theme);
        }
    }

    protected function updateTheme($name)
    {
        // Create adapter to make ThemeRepository compatible with Updater
        $adapter = new ThemeRepositoryAdapter(
            $this->laravel[ThemeContract::class],
            $this->laravel['files']
        );

        if ($name instanceof Theme) {
            $theme = $name;
            $name = $theme->name();
        } else {
            $theme = $this->laravel[ThemeContract::class]->findOrFail($name);
        }

        $this->components->task("Updating {$theme->name()} theme", function () use ($adapter, $name) {
            $updater = new Updater($adapter);
            $updater->update($name);
        });
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['theme', InputArgument::OPTIONAL, 'The name of theme will be updated.'],
        ];
    }
}
