<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ThemeGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'theme:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Theme Folder Structure';

    /**
     * Theme Folder Path.
     *
     * @var string
     */
    protected string $themePath;

    /**
     * Create Theme Info.
     *
     * @var array
     */
    protected array $theme;

    /**
     * Created Theme Structure.
     *
     * @var array
     */
    protected array $themeFolders;

    /**
     * Theme Stubs.
     *
     * @var string
     */
    protected string $themeStubPath;

    public function handle(): void
    {
        $this->themePath = config('themes.path');
        $this->themeFolders = config('themes.stubs.folders');
        $this->theme['name'] = $this->argument('name');
        $this->themeStubPath = $this->getThemeStubPath();
        $this->init();
    }

    /**
     * Theme Initialize.
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function init(): void
    {
        $createdThemePath = $this->themePath . '/' . Str::studly($this->theme['name']);
        $force = $this->option('force');

        if (!$force && File::isDirectory($createdThemePath)) {
            $this->error('Sorry, ' . Str::studly($this->theme['name']) . ' Theme Folder Already Exist !!!');
            exit();
        }

        $this->generateThemeInfo();

        $themeStubFiles = config('themes.stubs.files');
        $themeStubFiles['theme'] = 'theme.json';
        $themeStubFiles['changelog'] = 'changelog.md';
        $this->makeDir($createdThemePath);

        foreach ($this->themeFolders as $folder) {
            $this->makeDir($createdThemePath . '/' . $folder);
        }

        $this->createStubs($themeStubFiles, $createdThemePath);

        $this->info(ucfirst($this->theme['name']) . ' Theme Folder Successfully Generated !!!');

        // if ($this->confirm('Are you want to activate this theme?', true)) {
        //     $this->call('theme:active', ['theme' => $this->theme['name']]);
        // }
    }

    /**
     * Console command ask questions.
     *
     * @return void
     */
    public function generateThemeInfo(): void
    {
        $this->theme['title'] = $this->option('title') ?? Str::ucfirst($this->theme['name']);
        $this->theme['description'] = $this->option('description')
            ?? Str::ucfirst($this->theme['name']) . ' description';
        $this->theme['author'] = $this->option('author');
        $this->theme['version'] = $this->option('ver');
    }

    /**
     * Make directory.
     *
     * @param  string  $directory
     *
     * @return void
     */
    protected function makeDir(string $directory): void
    {
        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Create theme stubs.
     *
     * @param  array  $themeStubFiles
     * @param  string  $createdThemePath
     * @throws FileNotFoundException
     */
    public function createStubs(array $themeStubFiles, string $createdThemePath): void
    {
        foreach ($themeStubFiles as $filename => $storePath) {
            if ($filename == 'changelog') {
                $filename = 'changelog' . pathinfo($storePath, PATHINFO_EXTENSION);
            } elseif ($filename == 'theme') {
                $filename = pathinfo($storePath, PATHINFO_EXTENSION);
            } elseif ($filename == 'css' || $filename == 'js') {
                $this->theme[$filename] = ltrim(
                    $storePath,
                    rtrim('assets', '/') . '/'
                );
            }

            $storePath = str_replace('[NAME]', Str::slug($this->theme['name'], '-'), $storePath);

            $themeStubFile = $this->themeStubPath . '/' . $filename . '.stub';
            $filePath = $createdThemePath . '/' . $storePath;

            if (! File::isDirectory(dirname($filePath))) {
                File::makeDirectory(dirname($filePath), 0755, true);
            }

            $this->makeFile($themeStubFile, $filePath);
        }
    }

    /**
     * Make file.
     *
     * @param  string  $file
     * @param  string  $storePath
     *
     * @return void
     * @throws FileNotFoundException
     */
    protected function makeFile(string $file, string $storePath): void
    {
        if (File::exists($file)) {
            $content = $this->replaceStubs(File::get($file));
            File::put($storePath, $content);
        }
    }

    /**
     * Replace Stub string.
     *
     * @param  string  $contents
     *
     * @return string
     */
    protected function replaceStubs(string $contents): string
    {
        $mainString = [
            '[NAME]',
            '[TITLE]',
            '[DESCRIPTION]',
            '[AUTHOR]',
            '[VERSION]',
            '[CSSNAME]',
            '[JSNAME]',
            '[NAMESPACE]',
            '[NAMESPACE_SHORT]',
        ];

        $replaceString = [
            Str::slug($this->theme['name'], '-'),
            $this->theme['title'],
            $this->theme['description'],
            $this->theme['author'],
            $this->theme['version'],
            $this->theme['css'] ?? 'assets/css/theme.css',
            $this->theme['js'] ?? 'assets/js/theme.js',
            'Juzaweb\\Themes\\' . Str::studly($this->theme['name']),
            Str::studly($this->theme['name']),
        ];

        return str_replace($mainString, $replaceString, $contents);
    }

    protected function getThemeStubPath(): string
    {
        return config('themes.stubs.path');
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'Theme Name'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['title', null, InputOption::VALUE_OPTIONAL, 'Theme Title'],
            ['description', null, InputOption::VALUE_OPTIONAL, 'Theme Description'],
            ['author', null, InputOption::VALUE_OPTIONAL, 'Theme Author', 'Author Name'],
            ['ver', null, InputOption::VALUE_OPTIONAL, 'Theme Version', '1.0'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force to overwrite existing theme'],
        ];
    }
}
