<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://cms.juzaweb.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Translations;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Juzaweb\Modules\Core\Translations\Contracts\Translation;
use Juzaweb\Modules\Core\Translations\Contracts\TranslationFinder;
use Symfony\Component\Finder\SplFileInfo;

class TranslationImporter
{
    protected Closure $progressCallback;

    public function __construct(
        protected Collection $module,
        protected TranslationFinder $translationFinder,
        protected Translation $translationManager,
        protected ?string $replace = null
    ) {
    }

    public function run(): int
    {
        return $this->importLocalTranslations() + $this->importMissingKeys();
    }

    public function importLocalTranslations(): int
    {
        $locales = $this->getLocalLocales();
        $total = 0;
        foreach ($locales as $locale) {
            $result = $this->getLocalTranslates($locale['code']);

            foreach ($result as $item) {
                $model = $this->translationManager->importTranslationLine(
                    array_merge(
                        $item,
                        [
                            'namespace' => $this->module->get('namespace'),
                            'locale' => $locale['code'],
                            'object_type' => $this->module->get('type'),
                            'object_key' => $this->module->get('key'),
                        ]
                    )
                );

                if (isset($this->progressCallback)) {
                    call_user_func($this->progressCallback, $model);
                }

                if ($model->wasRecentlyCreated) {
                    ++ $total;
                }
            }
        }

        return $total;
    }

    public function importMissingKeys(): int
    {
        $srcPath = $this->module->get('src_path');

        if (! $srcPath) {
            return 0;
        }

        $results = $this->translationFinder->find($srcPath, replace: $this->replace);

        // if ($this->module->get('type') != 'cms') {
        //     $results = collect($results)->filter(fn ($item) => $item['namespace'] != 'cms')->toArray();
        // }

        $total = 0;
        foreach ($results as $item) {
            $item['object_type'] = $this->module->get('type');
            $item['object_key'] = $this->module->get('key');
            $model = $this->translationManager->importTranslationLine($item);

            if (isset($this->progressCallback)) {
                call_user_func($this->progressCallback, $model);
            }

            if ($model->wasRecentlyCreated) {
                ++$total;
            }
        }

        return $total;
    }

    public function progressCallback(Closure $progressCallback): static
    {
        $this->progressCallback = $progressCallback;

        return $this;
    }

    protected function getLocalLocales(): array
    {
        $folderPath = $this->module->get('lang_path');
        if (! is_dir($folderPath)) {
            return [];
        }

        $locales = collect(File::directories($folderPath))
            ->map(fn ($item) => basename($item))
            ->values()
            ->toArray();

        $files = collect(File::files($folderPath))
            ->filter(fn (SplFileInfo $item) => $item->getExtension() == 'json')
            ->map(fn (SplFileInfo $item) => $item->getFilenameWithoutExtension())
            ->values()
            ->toArray();

        $locales = array_merge($locales, $files);

        return collect(config('locales'))
            ->whereIn('code', $locales)
            ->toArray();
    }

    /**
     * Get all language trans
     *
     * @param string $locale
     * @return array
     * @throws \Exception
     */
    protected function getLocalTranslates(string $locale = 'en'): array
    {
        $result = [];
        $jsonFile = $this->module->get('lang_path') . "/{$locale}.json";
        if (File::exists($jsonFile)) {
            $this->mapGroupKeys(
                json_decode(File::get($jsonFile), true, 512, JSON_THROW_ON_ERROR),
                '*',
                $result,
                '',
                '*'
            );
        }

        $transFolder = $this->module->get('lang_path') . "/{$locale}";

        if (! is_dir($transFolder)) {
            return $result;
        }

        $files = File::files($transFolder);
        $files = collect($files)
            ->filter(fn ($item) => $item->getExtension() == 'php')
            ->values()
            ->toArray();

        foreach ($files as $file) {
            $lang = require($file->getRealPath());
            $group = str_replace('.php', '', $file->getFilename());
            $this->mapGroupKeys($lang, $group, $result);
        }

        return $result;
    }

    protected function mapGroupKeys(array $lang, $group, &$result, $keyPrefix = '', ?string $namespace = null): void
    {
        foreach ($lang as $key => $item) {
            if (is_array($item)) {
                $prefix = "{$keyPrefix}{$key}.";
                $this->mapGroupKeys($item, $group, $result, $prefix, $namespace);
            } else {
                $result[] = array_merge(
                    [
                        'key' => $keyPrefix . $key,
                        'value' => $item,
                        'group' => $group
                    ],
                    $namespace ? ['namespace' => $namespace] : []
                );
            }
        }
    }
}
