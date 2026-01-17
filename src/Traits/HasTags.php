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

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Juzaweb\Modules\Core\Models\Tag;

trait HasTags
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            Tag::class,
            'taggable',
            'taggable',
            'taggable_id',
            'tag_id'
        );
    }

    public function syncTags(array $tags = []): void
    {
        $tagIds = collect($tags)->map(
            function ($tag) {
                if (is_numeric($tag)) {
                    return (int) $tag;
                }

                return Tag::firstOrCreate(
                    [
                        'name' => $tag,
                    ]
                )->id;
            }
        );

        $this->tags()->sync($tagIds);
    }
}
