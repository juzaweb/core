<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\Core\Traits\HasSlug;
use Juzaweb\FileManager\Traits\HasMedia;

class PageTranslation extends Model
{
    use HasMedia, HasSlug;

    protected $table = 'page_translations';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'locale',
        'thumbnail',
    ];

    protected $appends = [
        'thumbnail',
    ];

    public $mediaChannels = [
        'thumbnail',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    public function getThumbnailAttribute(): ?string
    {
        return $this->getFirstMedia('thumbnail')?->path;
    }

    public function setThumbnailAttribute($value): void
    {
        if (is_null($value)) {
            $this->detachMedia('thumbnail');
            return;
        }

        $this->attachMedia($value, 'thumbnail');
    }
}
