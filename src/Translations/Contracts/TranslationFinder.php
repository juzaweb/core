<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcom/larabiz
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://larabiz.com
 * @license    MIT
 */

namespace Juzaweb\Modules\Core\Translations\Contracts;

use Juzaweb\Modules\Core\Translations\TranslationExporter;
use Juzaweb\Modules\Core\Translations\TranslationImporter;

/**
 * @see \Juzaweb\Modules\Core\Translations\TranslationFinder
 */
interface TranslationFinder
{
    /**
     * Find translations in the specified path and locale.
     *
     * @param string $path
     * @param string $locale
     * @return array
     */
    public function find(string $path, string $locale = 'en', ?string $replace = null): array;

    /**
     * Export translations for a specific module.
     *
     * @param string $module
     * @return TranslationExporter
     */
    public function export(string $module): TranslationExporter;

    /**
     * Import translations for a specific module.
     *
     * @param string $module
     * @return TranslationImporter
     */
    public function import(string $module): TranslationImporter;
}
