<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations;

use Astrotomic\Translatable\Locales as BaseLocales;
use Illuminate\Support\Facades\Schema;
use Juzaweb\Modules\Core\Translations\Exceptions\LocalesNotDefinedException;
use Juzaweb\Modules\Core\Translations\Models\Language;

class Locales extends BaseLocales
{
    public function load(): void
    {
        $localesConfig = ['en', 'vi'];
        if (Schema::hasTable('languages')) {
            $localesConfig = Language::pluck('code')->toArray();
        }

        if (empty($localesConfig)) {
            throw LocalesNotDefinedException::make();
        }

        $this->locales = [];
        foreach ($localesConfig as $key => $locale) {
            if (is_string($key) && is_array($locale)) {
                $this->locales[$key] = $key;

                foreach ($locale as $country) {
                    $countryLocale = $this->getCountryLocale($key, $country);
                    $this->locales[$countryLocale] = $countryLocale;
                }
            } elseif (is_string($locale)) {
                $this->locales[$locale] = $locale;
            }
        }
    }
}
