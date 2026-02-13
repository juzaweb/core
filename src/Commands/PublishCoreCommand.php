<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class PublishCoreCommand extends Command
{
    protected $name = 'core:publish';

    protected $description = 'Publish core assets to public directory';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'core-assets',
            '--force' => $this->option('force') ?? true,
            '--ansi' => true,
        ]);

        $this->info('Core assets published successfully!');

        $currentTheme = theme()->current();

        if ($currentTheme) {
            $this->call('theme:publish', [
                'theme' => $currentTheme->name(),
                // '--force' => $this->option('force') ?? true,
            ]);

            $this->info("Assets for theme '{$currentTheme->name()}' published successfully!");
        }

        return Command::SUCCESS;
    }

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Overwrites any existing files.'],
        ];
    }
}
