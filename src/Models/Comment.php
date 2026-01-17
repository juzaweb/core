<?php

/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Juzaweb\Modules\Core\Enums\CommentStatus;
use Juzaweb\Modules\Core\Traits\HasAPI;

class Comment extends Model
{
    use HasAPI, HasUuids;

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
     * Get the object that was commented on.
     *
     * This method returns the model that the comment is associated with,
     * such as a Video, Post, etc.
     *
     * @return MorphTo
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who made the comment.
     */
    public function commented(): MorphTo
    {
        return $this->morphTo();
    }

    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(__CLASS__, 'parent_id', 'id');
    }

    /**
     * Scope a query to only include approved comments.
     *
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeWhereFrontend(Builder $builder): Builder
    {
        return $builder
            ->cacheFor(3600)
            ->where("{$this->table}.status", CommentStatus::APPROVED);
    }

    public function getNameAttribute()
    {
        if ($this->commented && isset($this->commented->name)) {
            return $this->commented->name;
        }

        return __('core::translation.guest');
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
