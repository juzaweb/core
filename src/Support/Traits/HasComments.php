<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @author     The Anh Dang
 *
 * @link       https://cms.juzaweb.com
 *
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Modules\Core\Models\Comment;

/**
 * @mixin Model
 */
trait HasComments
{
    /**
     * Get the comments for the model.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the comment count for the model.
     */
    public function getTotalComments(): int
    {
        return $this->comments()->count();
    }
}
