<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 */

namespace Juzaweb\Core\Commands;

use Illuminate\Console\Command;
use Juzaweb\Core\Contracts\Translator;
use Juzaweb\Translations\Models\Translation;
use Symfony\Component\Console\Input\InputArgument;

class TranslateCommand extends Command
{
    protected $name = 'translation:translate';

    public function handle(): int
    {
        $target = $this->argument('target');
        $source = $this->argument('source');

        Translation::where('locale', $source)
            ->whereDoesntHave(
            'sameKeyTranslations',
            fn ($q) => $q->where('locale', $target)
        )
            ->chunk(100, function ($translations) use ($target, $source) {
                foreach ($translations as $translation) {
                    $newTranslation = $translation->replicate();
                    $newTranslation->locale = $target;
                    $newTranslation->value = app(Translator::class)->translate(
                        $translation->value,
                        $source,
                        $target,
                    );
                    $newTranslation->save();

                    $this->info("Translated {$translation->key} from {$source} to {$target}");
                    sleep(1);
                }
            }
        );

        $this->info('Done!');

        return self::SUCCESS;
    }

    protected function getArguments(): array
    {
        return [
            ['target', InputArgument::REQUIRED, 'The target locale translate.'],
            ['source', InputArgument::REQUIRED, 'The source locale to translate.'],
        ];
    }
}
