<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Modules\Core\Permissions\Models;

use Juzaweb\Modules\Core\Models\Model;

class Group extends Model
{
    protected $keyType = 'string';

    protected $primaryKey = 'code';

    protected $table = 'permissions_groups';

    protected $fillable = [
        'code',
        'name',
        'description',
        'priority',
    ];

    protected $casts = [
        'priority' => 'integer',
    ];
}
