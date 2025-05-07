<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSocialConnection
{
    public function socialConnections(): HasMany
    {
        return $this->hasMany(config("auth.providers.{$this->getTable()}.social_connections"));
    }
}
