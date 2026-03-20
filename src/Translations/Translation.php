<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations;

use Juzaweb\Modules\Core\Translations\Models\LanguageLine;

class Translation
{
    protected static string $languageLineModel = LanguageLine::class;

    public static function languageLineModel(): string
    {
        return static::$languageLineModel;
    }

    public static function useLanguageLineModel(string $model): void
    {
        static::$languageLineModel = $model;
    }
}
