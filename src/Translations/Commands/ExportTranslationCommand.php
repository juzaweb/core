<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://cms.juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Translations\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Translations\Contracts\Translation;
use Juzaweb\Modules\Core\Translations\Contracts\TranslationFinder;
use Symfony\Component\Console\Input\InputOption;

class ExportTranslationCommand extends Command
{
    protected $name = 'translation:export';

    public function handle(): int
    {
        if ($module = $this->option('module')) {
            $modules = [$module => app(Translation::class)->find($module)];
        } else {
            $modules = app(Translation::class)->modules();
        }

        foreach ($modules as $module => $options) {
            $exporter = app(TranslationFinder::class)->export($module);

            if ($language = $this->option('language')) {
                $exporter->setLanguage($language);
            }

            $export = $exporter->run();

            $this->info("Export success {$export} files from {$module}.");
        }

        return self::SUCCESS;
    }

    protected function getOptions(): array
    {
        return [
            ['module', 'm', InputOption::VALUE_OPTIONAL, 'The module to import', null],
            ['language', null, InputOption::VALUE_OPTIONAL, 'The name of plugin will be import.', null],
        ];
    }
}
