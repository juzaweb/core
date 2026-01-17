<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations\Traits;

use GuzzleHttp\Exception\ConnectException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Juzaweb\Modules\Core\Translations\Contracts\Translator;
use Juzaweb\Modules\Core\Translations\Enums\TranslateHistoryStatus;
use Juzaweb\Modules\Core\Translations\Exceptions\TranslationDoesNotExistException;
use Juzaweb\Modules\Core\Translations\Exceptions\TranslationExistException;
use Juzaweb\Modules\Core\Translations\Models\TranslateHistory;
use Throwable;

/**
 * @property array $translatedAttributes
 * @property array $translatedAttributeFormats
 * @method static Builder|static withTranslationAndMedia(?string $locale = null, ?array $with = null, bool $cache = false, array $cacheTags = [])
 * @method static Builder|static withTranslationOnForm(?string $locale = null, ?array $with = null)
 * @method static Builder|static translatedIn(?string $locale = null)
 * @method static Builder|static whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|static withTranslation(?string $locale = null, ?array $with = null, bool $cache = false, array $cacheTags = [])
 */
trait Translatable
{
    use \Astrotomic\Translatable\Translatable;

    public function translateHistories(): MorphMany
    {
        return $this->morphMany(TranslateHistory::class, 'translateable');
    }

    /**
     * @param  string  $locale
     * @return null|TranslateHistory
     */
    public function getTranslateHistory(string $locale): ?TranslateHistory
    {
        return $this->translateHistories()->where('locale', $locale)->first();
    }

    public function scopeWithTranslation(
        Builder $query,
        ?string $locale = null,
        ?array $with = null,
        bool $cache = false,
        array $cacheTags = []
    ) : Builder {
        $locale = $locale ?: $this->locale();

        return $query->with([
            'translations' => function (Relation $query) use ($locale, $with, $cache, $cacheTags) {
                if ($this->useFallback()) {
                    $countryFallbackLocale = $this->getFallbackLocale($locale); // e.g. de-DE => de
                    $locales = array_unique([$locale, $countryFallbackLocale, $this->getFallbackLocale()]);

                    return $query
                        ->when($with, fn ($q) => $q->with($with))
                        ->when($cache, fn ($q) => $q->cacheFor(3600))
                        ->when($cache && $cacheTags, fn ($q) => $q->cacheTags($cacheTags))
                        ->whereIn($this->getTranslationsTable().'.'.$this->getLocaleKey(), $locales);
                }

                return $query
                    ->when($with, fn ($q) => $q->with($with))
                    ->when($cache, fn ($q) => $q->cacheFor(3600))
                    ->when($cache && $cacheTags, fn ($q) => $q->cacheTags($cacheTags))
                    ->where($this->getTranslationsTable().'.'.$this->getLocaleKey(), $locale);
            },
        ]);
    }

    public function scopeWithTranslationOnForm(Builder $query, ?string $locale = null, ?array $with = null): Builder
    {
        $locale = $locale ?: setting('language', config('translatable.fallback_locale'));

        return $query->withTranslation($locale);
    }

    public function getTranslatedFields(): array
    {
        return $this->translatedAttributes ?? [];
    }

    public function translateTo(string $locale, string $source = 'en', array $options = []): bool
    {
        $options = array_merge(
            [
                'force' => false,
            ],
            $options
        );

        if (!$options['force'] && $this->hasTranslation($locale)) {
            throw TranslationExistException::make($locale);
        }

        $translation = $this->translate($source);
        $translateHistory = $this->getTranslateHistory($locale);

        if ($translation === null) {
            throw TranslationDoesNotExistException::make($source);
        }

        try {
            $translated = [];
            foreach ($this->translatedAttributes as $translatedAttribute) {
                if (! isset($translation->{$translatedAttribute})) {
                    $translated[$translatedAttribute] = null;
                    continue;
                }

                if ($translatedAttribute === 'slug'
                    || Arr::get($this->translatedAttributeFormats, $translatedAttribute) == 'slug'
                ) {
                    $translated[$translatedAttribute] = null;
                    continue;
                }

                $translated[$translatedAttribute] = app(Translator::class)->translate(
                    $translation->{$translatedAttribute},
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
            function () use ($locale, $translated, $translation, $translateHistory) {
                $translateHistory?->update(['status' => TranslateHistoryStatus::SUCCESS]);

                if ($newTranslation = $this->translate($locale)) {
                    $translated = array_filter($translated);
                    unset($translated['locale'], $translated['slug']);
                    return $newTranslation->update($translated);
                }

                $newTranslation = $translation->replicate();
                $newTranslation->fill($translated);
                $newTranslation->locale = $locale;
                return $newTranslation->save();
            }
        );
    }
}
