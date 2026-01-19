<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Facades\Theme;
use Juzaweb\Modules\Core\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakePageBlockCommand extends Command
{
    protected $name = 'theme:make-block';

    protected $description = 'Create a new page block class';

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

        $formPath = $theme->path("resources/views/components/blocks/{$name}/form.blade.php");
        $viewPath = $theme->path("resources/views/components/blocks/{$name}/view.blade.php");

        if (file_exists($formPath) || file_exists($viewPath)) {
            if (!$this->option('force')) {
                $this->error("Block {$name} already exists!");
                return self::FAILURE;
            }
        }

        if (!File::isDirectory(dirname($viewPath))) {
            File::makeDirectory(dirname($viewPath), 0755, true);
        }

        file_put_contents(
            $formPath,
            $this->generateContents('blocks/form.stub', ['NAME' => $name])
        );

        $this->info("Generated {$formPath}");

        file_put_contents(
            $viewPath,
            $this->generateContents('blocks/view.stub', ['NAME' => $name])
        );

        $this->info("Generated {$viewPath}");

        $providerFile = $theme->path('Providers/StyleServiceProvider.php');
        if (!file_exists($providerFile)) {
            $content = $this->generateContents(
                'provider.stub',
                [
                    'NAMESPACE' => 'Juzaweb\\Themes\\' . Str::studly($theme->name()) . '\\Providers',
                    'CLASS' => 'StyleServiceProvider',
                ]
            );
        } else {
            $content = file_get_contents($providerFile);
        }

        $pattern = '/(public function boot\s*\(\)\s*\{)([\s\S]*?)(^\s*\})/m';
        $replacement = '$1$2' . "        PageBlock::make(
            '{$name}',
            function () {
                return [
                    'label' => __('" . title_from_key($name) . "'),
                    'form' => '{$themeName}::components.blocks.{$name}.form',
                    'view' => '{$themeName}::components.blocks.{$name}.view',
                ];
            }
        );\n" . '$3';

        $newContent = preg_replace($pattern, $replacement, $content);
        $useStatement = "use Juzaweb\\Modules\\Admin\\Facades\\PageBlock;";

        if (!str_contains($newContent, $useStatement)) {
            // Tìm vị trí cuối cùng của nhóm use hiện tại
            if (preg_match_all('/^use\s+[^;]+;/m', $newContent, $allMatches, PREG_OFFSET_CAPTURE)) {
                // $allMatches[0] is an array of matches; pick the last one
                $lastMatch = end($allMatches[0]);
                // $lastMatch is [matchedString, offset]
                $matchedString = $lastMatch[0];
                $matchedOffset = $lastMatch[1];

                $insertPos = $matchedOffset + strlen($matchedString);
                $newContent = substr_replace($newContent, "\n{$useStatement}", $insertPos, 0);
            } else {
                // If there is no use block, add after namespace
                $newContent = preg_replace(
                    '/(namespace\s+[^\n;]+;)/',
                    "$1\n\n{$useStatement}",
                    $newContent
                );
            }
        }

        file_put_contents($providerFile, $newContent);

        $this->info("Block {$name} created successfully.");
        return self::SUCCESS;
    }

    protected function generateContents(string $stub, array $data): string
    {
        return (new Stub($stub, $data))->render();
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of block will be make.'],
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
