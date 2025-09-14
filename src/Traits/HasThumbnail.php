<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Traits;

trait HasThumbnail
{
    public function getThumbnailAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnail');
    }

    public function setThumbnailAttribute($value): void
    {
        $this->mediaAttributes['thumbnail'] = $value;
    }
}
