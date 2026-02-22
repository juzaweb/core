<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Traits;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Translations\Contracts\Translator;
use Juzaweb\Modules\Core\Translations\Enums\TranslateHistoryStatus;
use Juzaweb\Modules\Core\Translations\Models\TranslateHistory;
use Throwable;

trait LocaleModel
{
    public function translateHistories(): MorphMany
    {
        return $this->morphMany(TranslateHistory::class, 'translateable');
    }

    public function getTranslateHistory(string $locale): ?TranslateHistory
    {
        return $this->translateHistories()->where('locale', $locale)->first();
    }

    public function getTranslatedFields(): array
    {
        return $this->translatedAttributes ?? [];
    }

    public function getLocaleKey(): string
    {
        return 'locale';
    }

    public function translateTo(string $locale, string $source = 'en', array $options = []): bool
    {
        $options = array_merge(
            [
                'force' => false,
            ],
            $options
        );

        $translateHistory = $this->getTranslateHistory($locale);

        try {
            $translated = [];
            $attributes = $this->getTranslatedFields();

            foreach ($attributes as $translatedAttribute) {
                $value = $this->getAttribute($translatedAttribute);

                if ($value === null) {
                    $translated[$translatedAttribute] = null;
                    continue;
                }

                if ($translatedAttribute === 'slug'
                    || Arr::get($this->translatedAttributeFormats ?? [], $translatedAttribute) == 'slug'
                ) {
                    $translated[$translatedAttribute] = null;
                    continue;
                }

                $translated[$translatedAttribute] = app(Translator::class)->translate(
                    $value,
                    $source,
                    $locale,
                    isset($this->translatedAttributeFormats[$translatedAttribute])
                    && $this->translatedAttributeFormats[$translatedAttribute] === 'html'
                );

                // Sleep to avoid rate limit
                usleep(500000); // 0.5 second
            }
        } catch (ConnectException $e) {
            $translateHistory?->markAsFailed(get_error_by_exception($e));

            return false;
        } catch (Throwable $e) {
            $translateHistory?->markAsFailed(get_error_by_exception($e));

            throw $e;
        }

        return DB::transaction(
            function () use ($locale, $translated, $translateHistory, $attributes) {
                $translateHistory?->update(['status' => TranslateHistoryStatus::SUCCESS]);

                $newTranslation = $this->replicate();
                $newTranslation->fill($translated);
                if (isset($attributes['slug'])) {
                    $newTranslation->setAttribute('slug', null);
                }
                $newTranslation->setAttribute($this->getLocaleKey(), $locale);

                return $newTranslation->save();
            }
        );
    }
}
