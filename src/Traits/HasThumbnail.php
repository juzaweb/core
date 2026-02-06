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

use Juzaweb\Modules\Core\FileManager\Traits\HasMedia;
use Juzaweb\Modules\Core\Models\Media;
use Juzaweb\Modules\Core\Models\Model;

/**
 * @mixin HasMedia|Model
 */
trait HasThumbnail
{
    protected static ?string $defaultThumbnail = null;

    public function initializeHasThumbnail()
    {
        $this->appends[] = 'thumbnail';
    }

    public static function defaultThumbnail(string $url): void
    {
        self::$defaultThumbnail = $url;
    }

    public function getThumbnailAttribute(): ?string
    {
        return $this->getFirstMediaUrl('thumbnail')
            ?? $this->getDefaultThumbnail()
            ?? self::$defaultThumbnail;
    }

    public function setThumbnail(Media|string|null $thumbnail): void
    {
        $this->attachOrUpdateMedia($thumbnail, 'thumbnail');
    }

    public function getDefaultThumbnail(): ?string
    {
        return null;
    }
}
