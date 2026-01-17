<?php

namespace Juzaweb\Modules\Core\Themes\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Contracts\Theme;

class ThemeListCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'theme:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available themes.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $themes = $this->laravel[Theme::class]->all();
        $headers = ['Name', 'Author', 'Version'];
        $output = [];
        foreach ($themes as $theme) {
            $output[] = [
                'Name' => $theme->get('name'),
                'Author' => $theme->get('author'),
                'Version' => $theme->get('version'),
            ];
        }

        $this->table($headers, $output);
    }
}
