<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeTemplateCommand extends GenerateCommand
{
    protected $name = 'theme:make-template';

    protected $description = 'Create a new template for a theme';

    public function handle(): int
    {
        $name = preg_replace("/\W/", '-', $this->argument('name'));
        $name = strtolower($name);

        $themeName = $this->argument('theme');
        $theme = Theme::find($themeName);

        if ($theme === null) {
            $this->error("Theme {$themeName} does not exists.");
            return self::FAILURE;
        }

        Stub::setBasePath(config('themes.stubs.path') . '/');
        $templatePath = $theme->path("resources/views/templates/$name.blade.php");

        if (file_exists($templatePath) && !$this->option('force')) {
            $this->error("Block {$name} already exists!");
            return self::FAILURE;
        }

        if ($name === 'home') {
            $this->error("Template name 'home' is index page reserved!");
            return self::FAILURE;
        }

        if (!File::isDirectory(dirname($templatePath))) {
            File::makeDirectory(dirname($templatePath), 0755, true);
        }

        file_put_contents(
            $templatePath,
            $this->generateContents('template.stub', [
                'NAME' => $name,
                'THEME_NAME' => $themeName,
            ])
        );

        $content = $this->getStyleProviderContents($theme);
        $addContent = "        PageTemplate::make(
            '{$name}',
            function () {
                return [
                    'label' => __('" . title_from_key($name) . "'),
                    'view' => '{$theme->name()}::templates.{$name}',
                    'blocks' => [
                        'content' => __('core::translation.content'),
                    ],
                ];
            }
        );\n";

        $content = $this->addToProviderBoot($addContent, $content);
        $useStatement = "Juzaweb\\Modules\\Admin\\Facades\\PageTemplate;";

        $newContent = $this->addUseClass($content, $useStatement);
        $this->writeStyleProvider($theme, $newContent);

        $this->info("Template {$templatePath} created successfully.");
        return self::SUCCESS;
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of Template will be make.'],
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
