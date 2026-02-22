<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations\Contracts;

use Astrotomic\Translatable\Contracts\Translatable as BaseTranslatable;

interface Translatable extends BaseTranslatable, CanBeTranslated
{
}
