<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Translations\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Modules\Core\Translations\Models\TranslateHistory;

interface CanBeTranslated
{
    /**
     * Get the translated fields of the model.
     *
     * @return array
     */
    public function getTranslatedFields(): array;

    /**
     * Translate the model to a specific locale.
     *
     * @param  string  $locale
     * @param  string  $source
     * @param  array  $options
     * @return bool
     */
    public function translateTo(string $locale, string $source = 'en', array $options = []): bool;

    /**
     * Get the translation histories of the model.
     *
     * @return MorphMany
     */
    public function translateHistories(): MorphMany;

    /**
     * Get the translation history of the model.
     *
     * @param  string  $locale
     * @return TranslateHistory|null
     */
    public function getTranslateHistory(string $locale): ?TranslateHistory;
}
