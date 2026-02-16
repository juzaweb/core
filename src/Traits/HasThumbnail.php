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

    protected static ?string $thumbnailSize = null;

    public static function defaultThumbnail(string $url): void
    {
        self::$defaultThumbnail = $url;
    }

    public static function thumbnailSize(string $size): void
    {
        self::$thumbnailSize = $size;
    }

    public function initializeHasThumbnail(): void
    {
        $this->appends[] = 'thumbnail';
    }

    public function getThumbnailAttribute(): ?string
    {
        return $this->getThumbnail();
    }

    public function getThumbnail(string $size = null): ?string
    {
        $url = $this->getFirstMediaUrl('thumbnail');

        if ($url) {
            $size = $size ?? self::$thumbnailSize;

            if ($size) {
                return proxy_image($url, ...explode('x', $size));
            }

            return $url;
        }

        return $this->getDefaultThumbnail() ?? self::$defaultThumbnail;
    }

    public function setThumbnail(Media|string|null $thumbnail): void
    {
        $this->attachOrUpdateMedia($thumbnail, 'thumbnail');
    }

    public function getDefaultThumbnail(): ?string
    {
        return null;
    }

    public function hasThumbnail(): bool
    {
        return $this->hasMedia('thumbnail');
    }
}
