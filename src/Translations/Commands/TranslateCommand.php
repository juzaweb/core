<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Translations\Commands;

use Illuminate\Console\Command;
use Juzaweb\Modules\Core\Translations\Contracts\Translator;
use Juzaweb\Modules\Core\Translations\Models\Translation;
use Symfony\Component\Console\Input\InputOption;

class TranslateCommand extends Command
{
    protected $name = 'translation:translate';

    public function handle(): int
    {
        if ($this->option('target')) {
            $targets = explode(',', $this->option('target'));
        } else {
            $targets = config('app.languages', []);
        }
        $source = $this->option('source') ?? config('translatable.fallback_locale');
        $module = $this->option('module');

        foreach ($targets as $target) {
            Translation::where('locale', $source)
                ->whereDoesntHave(
                    'sameKeyTranslations',
                    fn($q) => $q->where('locale', $target)
                )
                ->when($module, function ($query) use ($module) {
                    $query->where(['object_key' => $module]);
                })
                ->chunk(
                    300,
                    function ($translations) use ($target, $source) {
                        foreach ($translations as $translation) {
                            $newTranslation = $translation->replicate();
                            $newTranslation->locale = $target;

                            // Protect placeholders before translation
                            [$protectedText, $placeholders] = $this->protectPlaceholders($translation->value);

                            // Translate the protected text
                            $translatedText = app(Translator::class)->translate(
                                $protectedText,
                                $source,
                                $target,
                            );

                            // Restore placeholders after translation
                            $newTranslation->value = $this->restorePlaceholders($translatedText, $placeholders);
                            $newTranslation->save();

                            $this->info("Translated {$translation->key} from {$source} to {$target}");
                            usleep(500000);
                        }
                    }
                );
        }

        $this->info('Done!');

        return self::SUCCESS;
    }

    /**
     * Protect Laravel placeholders from being translated
     *
     * @param string $text
     * @return array [protected text, placeholders array]
     */
    protected function protectPlaceholders(string $text): array
    {
        $placeholders = [];
        $index = 0;

        // Match Laravel-style placeholders like :name, :attribute, :value, etc.
        $protectedText = preg_replace_callback(
            '/:[a-zA-Z_][a-zA-Z0-9_]*/',
            function ($matches) use (&$placeholders, &$index) {
                $placeholder = $matches[0];
                $token = "__PLACEHOLDER_{$index}__";
                $placeholders[$token] = $placeholder;
                $index++;
                return $token;
            },
            $text
        );

        return [$protectedText, $placeholders];
    }

    /**
     * Restore Laravel placeholders after translation
     *
     * @param string $text
     * @param array $placeholders
     * @return string
     */
    protected function restorePlaceholders(string $text, array $placeholders): string
    {
        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    protected function getOptions(): array
    {
        return [
            ['target', null, InputOption::VALUE_OPTIONAL, 'The target locale to translate to.'],
            ['source', null, InputOption::VALUE_OPTIONAL, 'The source locale to translate from.'],
            ['theme', null, InputOption::VALUE_OPTIONAL, 'The theme to translate.'],
            ['module', null, InputOption::VALUE_OPTIONAL, 'The module to translate.'],
        ];
    }
}
