<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Themes\Theme;
use Symfony\Component\Console\Input\InputArgument;

class DownloadTemplateCommand extends DownloadCommand
{
    protected $name = 'theme:download-template';

    protected array $data = [];

    protected ?Theme $theme;

    public function handle(): void
    {
        $this->theme = \Juzaweb\Modules\Core\Facades\Theme::find($this->argument('theme'));
        if ($this->theme === null) {
            $this->error('Theme not found!');
            return;
        }

        $this->sendBaseDataAsks();

        $this->downloadFileAsks();
    }

    protected function sendBaseDataAsks(): void
    {
        $this->data['url'] = $this->ask(
            'Url Template?',
            $this->getDataDefault('url')
        );

        $this->setDataDefault('url', $this->data['url']);

        $this->data['container'] = $this->ask(
            'Container?',
            $this->getDataDefault('container', '.container-fluid')
        );

        $this->setDataDefault('container', $this->data['container']);

        $this->data['file'] = $this->ask(
            'File?',
            $this->getDataDefault('file', 'index.blade.php')
        );

        $this->setDataDefault('file', $this->data['file']);
    }

    protected function downloadFileAsks(): void
    {
        $themeName = $this->theme->name();
        $extension = pathinfo($this->data['file'], PATHINFO_EXTENSION);
        if ($extension != 'php') {
            $this->data['file'] = "{$this->data['file']}.blade.php";
        }

        $output = $this->theme->path('resources/views');
        $path = "{$output}/{$this->data['file']}";

        if (!File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        $contents = $this->getFileContent($this->data['url']);
        $content = str_get_html($contents)->find($this->data['container'], 0)->outertext;

        File::put(
            $path,
            "@extends('{$themeName}::layouts.main')

@section('content')
    {$content}
@endsection"
        );

        $this->info("-- Downloaded file {$path}");
    }

    protected function getArguments(): array
    {
        return [
            ['theme', InputArgument::REQUIRED, 'Theme name'],
        ];
    }
}
