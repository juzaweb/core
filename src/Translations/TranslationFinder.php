<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcom/larabiz
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://cms.juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Translations;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Juzaweb\Modules\Core\Translations\Contracts\Translation;
use Juzaweb\Modules\Core\Translations\Contracts\TranslationFinder as TranslationFinderContract;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class TranslationFinder implements TranslationFinderContract
{
    public function __construct(
        protected Translation $translation
    ) {
    }

    public function find(string $path, string $locale = 'en', ?string $replace = null): array
    {
        $groupKeys = [];
        $stringKeys = [];
        $functions = [
            'trans',
            'trans_choice',
            'Lang::get',
            'Lang::choice',
            'Lang::trans',
            'Lang::transChoice',
            '@lang',
            '@choice',
            '__',
            '$trans.get',
            't',
        ];

        $groupPattern =                          // See https://regex101.com/r/WEJqdL/6
            "[^\w|>]" .                          // Must not have an alphanum or _ or > before real method
            '(' . implode('|', $functions) . ')' .  // Must start with one of the functions
            "\(" .                               // Match opening parenthesis
            "[\'\"]" .                           // Match " or '
            '(' .                                // Start a new group to match:
            '[\/a-zA-Z0-9\_\-\:]+' .             // Must start with group
            "([.](?! )[^\1)]+)+" .               // Be followed by one or more items/keys
            ')' .                                // Close group
            "[\'\"]" .                           // Closing quote
            "[\),]";                             // Close parentheses or new parameter

        $stringPattern = "[^\w]". // Must not have an alphanum before real method
            '('.implode('|', $functions).')'.             // Must start with one of the functions
            "\(\s*".                                       // Match opening parenthesis
            "(?P<quote>['\"])".                            // Match " or ' and store in {quote}
            "(?P<string>(?:\\\k{quote}|(?!\k{quote}).)*)". // Match any string that can be {quote} escaped
            "\k{quote}".                                   // Match " or ' previously matched
            "\s*[\),]";                                    // Close parentheses or new parameter

        // Find all PHP + Twig files in the app folder, except for storage
        $finder = $this->getAllFiles($path);
        $results = [];

        /** @var SplFileInfo $file */
        foreach ($finder as $file) {
            // Search the current file for the pattern
            if (preg_match_all("/{$groupPattern}/siU", $file->getContents(), $matches)) {
                // Get all matches
                foreach ($matches[2] as $key) {
                    $groupKeys[] = $key;
                }
            }

            if (preg_match_all("/$stringPattern/siU", $file->getContents(), $matches)) {
                if ($replace) {
                    $pattern = '/(?<func>\b(?:' . implode('|', array_map('preg_quote', $functions))
                    . '))\(\s*(["\'])(?<key>[^"\']+)\2/';

                    $result = preg_replace_callback(
                        $pattern,
                        function ($matches) use ($replace, $locale, &$results) {
                            $func = $matches['func'];
                            $quote = $matches[2];
                            $key = $matches['key'];

                            if ((Str::contains($key, '::') && Str::contains($key, '.'))
                                && !Str::contains($key, ' ')
                            ) {
                                return $matches[0];
                            }

                            $newKey = Str::slug($key, '_');
                            $results[] = [
                                'namespace' => explode('::', $replace)[0] ?? '*',
                                'locale' => $locale,
                                'group' => explode('::', $replace)[1] ?? '*',
                                'key' => $newKey,
                                'value' => $key,
                            ];

                            $newKey = "{$replace}.{$newKey}";

                            return "{$func}({$quote}{$newKey}{$quote}";
                        },
                        $file->getContents()
                    );

                    if ($result !== null) {
                        file_put_contents(
                            $file->getRealPath(),
                            $result
                        );
                    }
                } else {
                    foreach ($matches['string'] as $key) {
                        if (preg_match(
                            "/(^[\/a-zA-Z0-9_-]+([.][^\1)\ ]+)+$)/siU",
                            $key,
                            $groupMatches
                        )) {
                            // group{.group}.key format, already in $groupKeys but also matched here
                            // do nothing, it has to be treated as a group
                            continue;
                        }

                        // TODO: This can probably be done in the regex, but I couldn't do it.
                        // skip keys which contain namespacing characters, unless they also contain a
                        // space, which makes it JSON.
                        if (!(Str::contains($key, '::') && Str::contains($key, '.'))
                            || Str::contains($key, ' ')
                        ) {
                            $stringKeys[] = $key;
                        }
                    }
                }
            }
        }

        // Remove duplicates
        $groupKeys = array_unique($groupKeys);
        $stringKeys = array_unique($stringKeys);

        // Add the translations to the database, if not existing.
        foreach ($groupKeys as $key) {
            // Split the group and item
            [$group, $item] = explode('.', $key, 2);
            $namespace = '*';
            if (Str::contains($key, '::')) {
                $namespace = explode('::', $key)[0];
                $group = str_replace("{$namespace}::", '', $group);
            }

            $value = Str::ucfirst(
                str_replace(["{$namespace}::", "{$group}.", '.', '_'], ['', '', ' ', ' '], $key)
            );

            $results[] = [
                'namespace' => $namespace,
                'locale' => $locale,
                'group' => $group,
                'key' => $item,
                'value' => $value,
            ];
        }

        foreach ($stringKeys as $key) {
            $namespace = '*';
            $group = '*';

            $results[] = [
                'namespace' => $namespace,
                'locale' => $locale,
                'group' => $group,
                'key' => $key,
                'value' => $key,
            ];
        }

        return $results;
    }

    public function export(string $module): TranslationExporter
    {
        $module = $this->translation->find($module);

        return $this->createTranslationExporter(collect($module));
    }

    public function import(string $module, ?string $replace = null): TranslationImporter
    {
        $module = $this->translation->find($module);

        return $this->createTranslationImporter(collect($module), $replace);
    }

    protected function getAllFiles(string $path): Finder
    {
        $finder = new Finder();
        return $finder->in($path)
            ->exclude('storage')
            ->exclude('vendor')
            ->name('*.php')
            ->name('*.twig')
            ->name('*.ts')
            ->name('*.tsx')
            ->files();
    }

    protected function createTranslationExporter(Collection $module): TranslationExporter
    {
        return new TranslationExporter($module);
    }

    protected function createTranslationImporter(Collection $module, ?string $replace): TranslationImporter
    {
        return new TranslationImporter(
            $module,
            $this,
            $this->translation,
            $replace
        );
    }
}
