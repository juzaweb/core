<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Facades\Theme;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeWidgetCommand extends Command
{
    protected $name = 'theme:make-widget';

    protected $description = 'Create a new widget for a theme';

    public function handle(): int
    {
        $name = preg_replace("/\W/", '-', $this->argument('name'));
        $name = strtolower($name);

        $themeName = $this->argument('theme');
        $theme = Theme::find($themeName);

        if ($theme === null) {
            $this->error("Theme {$themeName} does not exist.");
            return self::FAILURE;
        }

        $viewPath = $theme->path("src/resources/views/components/widgets/{$name}/show.blade.php");
        $formPath = $theme->path("src/resources/views/components/widgets/{$name}/form.blade.php");

        if (file_exists($viewPath) || file_exists($formPath)) {
            if (!$this->option('force')) {
                $this->error("Widget {$name} already exists!");
                return self::FAILURE;
            }
        }

        if (!File::isDirectory(dirname($viewPath))) {
            File::makeDirectory(dirname($viewPath), 0755, true);
        }

        // Generate View
        file_put_contents($viewPath, $this->getViewStub());
        $this->info("Generated {$viewPath}");

        // Generate Form
        file_put_contents($formPath, $this->getFormStub());
        $this->info("Generated {$formPath}");

        // Inject into Provider
        $this->injectIntoProvider($theme, $name);

        $this->info("Widget {$name} created successfully.");

        return self::SUCCESS;
    }

    protected function injectIntoProvider($theme, $name)
    {
        $providerFile = $theme->path('src/Providers/ThemeServiceProvider.php');

        if (!file_exists($providerFile)) {
            $this->warn("ThemeServiceProvider.php not found at {$providerFile}");
            return;
        }

        $content = file_get_contents($providerFile);
        $themeName = $theme->name();
        $title = Str::title(str_replace('-', ' ', $name));

        $stub = "        Widget::make(
            '{$name}',
            function () {
                return [
                    'label' => __('" . $title . "'),
                    'description' => __('" . $title . " Widget'),
                    'view' => '{$themeName}::components.widgets.{$name}.show',
                    'form' => '{$themeName}::components.widgets.{$name}.form',
                ];
            }
        );\n";

        $pattern = '/(public function boot\s*\(\)\s*\{)([\s\S]*?)(^\s*\})/m';
        if (preg_match($pattern, $content)) {
            $replacement = '$1$2' . "\n" . $stub . '$3';
            $newContent = preg_replace($pattern, $replacement, $content);

            // Add Use statement
            $useStatement = "use Juzaweb\\Modules\\Core\\Facades\\Widget;";
            if (!str_contains($newContent, $useStatement)) {
                if (preg_match_all('/^use\s+[^;]+;/m', $newContent, $allMatches, PREG_OFFSET_CAPTURE)) {
                    $lastMatch = end($allMatches[0]);
                    $insertPos = $lastMatch[1] + strlen($lastMatch[0]);
                    $newContent = substr_replace($newContent, "\n{$useStatement}", $insertPos, 0);
                } else {
                    $newContent = preg_replace(
                        '/(namespace\s+[^\n;]+;)/',
                        "$1\n\n{$useStatement}",
                        $newContent
                    );
                }
            }
            file_put_contents($providerFile, $newContent);
            $this->info("Registered widget in ThemeServiceProvider.");
        } else {
            $this->warn("Could not find boot method in ThemeServiceProvider to register widget.");
        }
    }

    protected function getViewStub()
    {
        return '<div class="widget">
    <!-- Widget Content -->
</div>';
    }

    protected function getFormStub()
    {
        return '<div class="row">
    <!-- Widget Form Fields -->
</div>';
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the widget.'],
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
