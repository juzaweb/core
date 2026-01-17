<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Traits;

trait HasFrontendUrl
{
    public function initializeHasFrontendUrl()
    {
        $this->appends[] = 'url';
    }

    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    abstract public function getUrl(): string;
}
