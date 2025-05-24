<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Models;

use Juzaweb\Core\Traits\HasAPI;
use Juzaweb\Translations\Models\Language as LanguageAlias;

class Language extends LanguageAlias
{
    use HasAPI;

    public string $cachePrefix = 'languages_';
}
