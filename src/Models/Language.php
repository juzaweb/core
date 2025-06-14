<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Models;

use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Translations\Models\Language as LanguageAlias;

class Language extends LanguageAlias
{
    use HasAPI;

    public string $cachePrefix = 'languages_';
}
