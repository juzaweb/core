<?php

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Juzaweb\Modules\Admin\Models\Website;
use Juzaweb\Modules\Blog\Models\Post;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;
use Juzaweb\Modules\StorySharing\Models\Story;

class Tag extends Model
{
    use HasAPI,  UsedInFrontend;

    protected $table = 'tags';

    protected $fillable = [
        'name',
    ];

    public $searchable = [
        'name',
    ];

    public function taggable()
    {
        return $this->morphTo();
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable', 'taggable');
    }

    public function stories()
    {
        return $this->morphedByMany(Story::class, 'taggable', 'taggable');
    }

    public function getUrl(): string
    {
        return home_url('search?tag=' . urlencode($this->name));
    }
}
