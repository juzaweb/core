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
use Juzaweb\QueryCache\QueryCacheable;
use Juzaweb\Translations\Models\Language as LanguageAlias;

class Language extends LanguageAlias
{
    use HasAPI, QueryCacheable;

    protected $table = 'languages';

    protected array $filterable = ['code', 'name'];

    protected array $searchable = ['code', 'name'];

    protected array $sortable = ['code', 'name'];

    protected array $sortDefault = ['created_at' => 'desc'];
}
