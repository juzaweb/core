<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property array $translatedAttributes
 * @property array $translatedAttributeFormats
 * @method static Builder|static withTranslationAndMedia(?string $locale = null, ?array $with = null, bool $cache = false, array $cacheTags = [])
 * @method static Builder|static withTranslationOnForm(?string $locale = null, ?array $with = null)
 * @method static Builder|static translatedIn(?string $locale = null)
 * @method static Builder|static whereTranslation(string $translationField, $value, ?string $locale = null, string $method = 'whereHas', string $operator = '=')
 * @method static Builder|static withTranslation(?string $locale = null, ?array $with = null, bool $cache = false, array $cacheTags = [])
 * @deprecated
 */
trait Translatable
{
    use \Juzaweb\Modules\Core\Traits\Translatable;
}
