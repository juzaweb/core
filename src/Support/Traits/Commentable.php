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

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Juzaweb\Modules\Core\Models\Comment;

trait Commentable
{
    /**
     * Get the comments for the model.
     */
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commented');
    }

    /**
     * Get the comment count for the model.
     */
    public function getTotalComments(): int
    {
        return $this->comments()->count();
    }
}
