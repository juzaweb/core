<?php

namespace Juzaweb\Modules\Core\Translations\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Translations\Contracts\Translation;
use Juzaweb\Modules\Core\Translations\Contracts\TranslationFinder;
use Symfony\Component\Console\Input\InputOption;

class ImportTranslationCommand extends Command
{
    protected $name = 'translation:import';

    public function handle(): int
    {
        if ($module = $this->option('module')) {
            $modules = [$module => app(Translation::class)->find($module)];
        } else {
            $modules = app(Translation::class)->modules();
        }

        foreach ($modules as $module => $options) {
            $replace = $this->option('replace') ? "{$options['namespace']}::translation" : null;
            $import = app(TranslationFinder::class)->import($module, $replace)->run();

            $this->info("Imported {$import} rows from {$module}");

            if ($this->option('export')) {
                $this->call('translation:export', [
                    '--module' => $module,
                ]);
            }
        }

        return static::SUCCESS;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['module', 'm', InputOption::VALUE_OPTIONAL, 'The module to import', null],
            ['replace', 'r', InputOption::VALUE_NONE, 'Replace existing translations'],
            ['export', 'e', InputOption::VALUE_NONE, 'Export translations after import'],
        ];
    }
}
