<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Juzaweb\Core\Media\Traits\HasMedia;
use Juzaweb\Core\Traits\HasSlug;

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

    public function getThumbnailAttribute(): ?Media
    {
        return $this->getFirstMedia('thumbnail');
    }

    public function setThumbnailAttribute($value): void
    {
        $this->attachMedia($value, 'thumbnail');
    }
}
