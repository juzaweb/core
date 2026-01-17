<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeControllerCommand extends GenerateCommand
{
    protected $name = 'theme:make-controller';

    protected $description = 'Create a new controller for a theme';

    public function handle(): int
    {
        $name = $this->argument('name');
        $themeName = $this->argument('theme');
        $theme = Theme::find($themeName);

        if ($theme === null) {
            $this->error("Theme {$themeName} does not exist.");
            return self::FAILURE;
        }

        // Ensure controller name ends with "Controller"
        $controllerName = Str::studly($name);
        if (Str::contains(strtolower($controllerName), 'controller') === false) {
            $controllerName .= 'Controller';
        }

        Stub::setBasePath(resource_path('stubs/'));
        $controllerPath = $theme->path("Http/Controllers/{$controllerName}.php");

        if (file_exists($controllerPath) && !$this->option('force')) {
            $this->error("Controller {$controllerName} already exists!");
            return self::FAILURE;
        }

        if (!File::isDirectory(dirname($controllerPath))) {
            File::makeDirectory(dirname($controllerPath), 0755, true);
        }

        file_put_contents(
            $controllerPath,
            $this->generateContents('themes/controllers/controller.stub', [
                'NAMESPACE_SHORT' => Str::studly($themeName),
                'CLASS' => $controllerName,
            ])
        );

        $this->info("Controller {$controllerPath} created successfully.");
        return self::SUCCESS;
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the controller.'],
            ['theme', InputArgument::REQUIRED, 'The name of the theme.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force overwrite existing files.'],
        ];
    }
}
