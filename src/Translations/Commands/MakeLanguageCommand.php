<?php

namespace Juzaweb\Core\Translations\Commands;

use Illuminate\Console\Command;
use Juzaweb\Core\Models\Language;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeLanguageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'language:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new language';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $code = $this->argument('code');
        $locale = config("locales.{$code}");

        if (! $locale) {
            $this->error("The language {$code} doesn't exist");

            return Command::FAILURE;
        }

        Language::create(['code' => $code, 'name' => $locale['name'], 'default' => $this->option('default')]);

        return Command::SUCCESS;
    }

    protected function getArguments(): array
    {
        return [
            ['code', InputArgument::OPTIONAL, 'The code of the language'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['default', 'd', InputOption::VALUE_NONE, 'Set the language as default'],
        ];
    }
}
