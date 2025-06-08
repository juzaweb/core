<?php

namespace Juzaweb\Core\Themes\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Juzaweb\Core\Facades\Theme;
use Symfony\Component\Console\Input\InputArgument;
use Juzaweb\Core\Themes\Theme as ThemeEntity;

class ThemePublishCommand extends Command
{
    protected $name = 'theme:publish';

    public function handle(): void
    {
        $name = $this->argument('theme');

        $type = $this->argument('type') ?? 'assets';

        $theme = Theme::find($name);

        if (! $theme) {
            $this->error('Theme not found');
            return;
        }

        switch ($type) {
            case 'views':
                $this->publishViews($theme);
                break;
            case 'lang':
                $this->publishLang($theme);
                break;
            case 'assets':
                $this->publishAssets($theme);
                break;
        }

        $this->info('Publish Theme Successfully');
    }

    protected function publishAssets(ThemeEntity $theme): void
    {
        $sourceFolder = $theme->path('assets/public');
        $publicFolder = public_path('themes/'. $theme->name());

        if (!File::isDirectory($publicFolder)) {
            File::makeDirectory($publicFolder, 0755, true, true);
        }

        File::copyDirectory($sourceFolder, $publicFolder);
    }

    protected function publishViews(ThemeEntity $theme): void
    {
        $sourceFolder = $theme->path('src');
        $publicFolder = resource_path('views/themes/'. $theme->name());

        if (!File::isDirectory($publicFolder)) {
            File::makeDirectory($publicFolder, 0755, true, true);
        }

        File::copyDirectory($sourceFolder, $publicFolder);
    }

    protected function publishLang(ThemeEntity $theme): void
    {
        $sourceFolder = $theme->path('lang');
        $publicFolder = resource_path('lang/themes/'. $theme->name());

        if (!File::isDirectory($publicFolder)) {
            File::makeDirectory($publicFolder, 0755, true, true);
        }

        File::copyDirectory($sourceFolder, $publicFolder);
    }

    protected function getArguments(): array
    {
        return [
            ['theme', InputArgument::REQUIRED, 'Theme publish name.', null],
            ['type', InputArgument::OPTIONAL, 'Type: assets, views, lang'],
        ];
    }
}
