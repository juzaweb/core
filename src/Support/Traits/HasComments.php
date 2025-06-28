<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Support\Traits;

use Juzaweb\Core\Models\Comment;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasComments
{
    /**
     * Get the comments for the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commented');
    }

    /**
     * Get the comment count for the model.
     *
     * @return int
     */
    public function getTotalComments()
    {
        return $this->comments()->count();
    }
}
