<?php

namespace Juzaweb\Modules\Core\Models;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Juzaweb\Modules\Blog\Models\Post;
use Juzaweb\Modules\Core\Traits\HasAPI;
use Juzaweb\Modules\Core\Traits\HasFrontendUrl;
use Juzaweb\Modules\Core\Traits\UsedInFrontend;

class Tag extends Model
{
    use HasAPI, UsedInFrontend, HasFrontendUrl;

    protected $table = 'tags';

    protected $fillable = [
        'name',
    ];

    public $searchable = [
        'name',
    ];

    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable', 'taggable');
    }

    public function getUrl(): string
    {
        return home_url('search?tag=' . urlencode($this->name));
    }
}
