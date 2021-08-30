<?php

namespace Juzaweb\Core\Models;

use Juzaweb\Core\Facades\PostType;
use Illuminate\Database\Eloquent\Builder;

/**
 * Juzaweb\Theme\Models\Comment
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $email
 * @property string|null $name
 * @property string|null $website
 * @property string $content
 * @property int $object_id Post type ID
 * @property string $object_type Post type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereObjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereWebsite($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'email',
        'name',
        'website',
        'content',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function postType()
    {
        $postType = PostType::getPostTypes($this->object_type);
        return $this->belongsTo($postType->get('model'), 'object_id', 'id')->where('object_type', '=', $this->object_type);
    }

    public function whereApproved(Builder $builder)
    {
        return $builder->where('status', '=', 'approved');
    }

    public static function allStatuses()
    {
        return apply_filters('comment.statuses', [
            'approved' => trans('juzaweb::app.approved'),
            'deny' => trans('juzaweb::app.deny'),
            'pending' => trans('juzaweb::app.pending'),
            'trash' => trans('juzaweb::app.trash'),
        ]);
    }
}
