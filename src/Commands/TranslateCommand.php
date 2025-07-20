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
use Juzaweb\Core\Models\Language;
use Juzaweb\Translations\Models\Translation;
use Symfony\Component\Console\Input\InputOption;

class TranslateCommand extends Command
{
    protected $name = 'translation:translate';

    public function handle(): int
    {
        if ($this->option('target')) {
            $targets = explode(',', $this->option('target'));
        } else {
            $targets = Language::languages()->keys()->filter(
                fn ($locale) => $locale !== config('translatable.fallback_locale')
            )->toArray();
        }
        $source = $this->option('source') ?? config('translatable.fallback_locale');

        foreach ($targets as $target) {
            Translation::where('locale', $source)
                ->whereDoesntHave(
                    'sameKeyTranslations',
                    fn ($q) => $q->where('locale', $target)
                )
                ->chunk(
                    300,
                    function ($translations) use ($target, $source) {
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
                    });
        }

        $this->info('Done!');

        return self::SUCCESS;
    }

    protected function getOptions(): array
    {
        return [
            ['target', null, InputOption::VALUE_OPTIONAL, 'The target locale to translate to.'],
            ['source', null, InputOption::VALUE_OPTIONAL, 'The source locale to translate from.'],
        ];
    }
}
