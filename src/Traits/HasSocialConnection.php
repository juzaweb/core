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

trait HasSocialConnection
{
    public function socialConnections(): HasMany
    {
        return $this->hasMany(config("auth.providers.{$this->getTable()}.social_connections"));
    }
}
