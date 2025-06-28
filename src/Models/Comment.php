<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Core\Models\Enums\CommentStatus;

class Comment extends Model
{
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'content',
        'status',
    ];

    protected $casts = [
        'status' => CommentStatus::class,
    ];

    /**
     * Get the parent commentable model (post, video, etc.).
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include approved comments.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeFrontend(Builder $builder): Builder
    {
        return $builder->where('status', CommentStatus::Approved);
    }

    /**
     * Get the status label.
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        return $this->status->label();
    }
}
