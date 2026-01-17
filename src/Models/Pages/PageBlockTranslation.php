<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Models\Pages;

use Juzaweb\Modules\Core\Models\Model;

class PageBlockTranslation extends Model
{
    protected $table = 'page_block_translations';

    protected $fillable = [
        'page_block_id',
        'locale',
        'label',
        'fields',
    ];

    protected $casts = [
        'fields' => 'array',
    ];
}
