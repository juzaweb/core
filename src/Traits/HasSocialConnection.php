<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Juzaweb\Core\Models\Users\UserSocialConnection;

trait HasSocialConnection
{
    public function socialConnections(): HasMany
    {
        return $this->hasMany(UserSocialConnection::class, 'user_id', 'id');
    }
}
