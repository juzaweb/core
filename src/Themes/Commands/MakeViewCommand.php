<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeViewCommand extends GenerateCommand
{
    protected $name = 'theme:make-view';

    protected $description = 'Create a new view for a theme';

    public function handle(): int
    {
        $name = strtolower($this->argument('name'));

        $themeName = $this->argument('theme');
        $theme = Theme::find($themeName);

        if ($theme === null) {
            $this->error("Theme {$themeName} does not exist.");
            return self::FAILURE;
        }

        Stub::setBasePath(config('themes.stubs.path') . '/');
        $viewPath = $theme->path("resources/views/$name.blade.php");

        if (file_exists($viewPath) && !$this->option('force')) {
            $this->error("View {$name} already exists!");
            return self::FAILURE;
        }

        if (!File::isDirectory(dirname($viewPath))) {
            File::makeDirectory(dirname($viewPath), 0755, true);
        }

        file_put_contents(
            $viewPath,
            $this->generateContents('view.stub', [
                'THEME_NAME' => $theme->lowerName(),
            ])
        );

        $this->info("View {$viewPath} created successfully.");
        return self::SUCCESS;
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view to be created.'],
            ['theme', InputArgument::REQUIRED, 'The name of theme.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force overwrite existing files.'],
        ];
    }
}
