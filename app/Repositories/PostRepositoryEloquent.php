<?php

namespace Juzaweb\Core\Repositories;

use Juzaweb\Core;
use Juzaweb\Core\Repository\Eloquent\BaseRepository;
use Juzaweb\Core\Models\Post;

/**
 * Class PostRepositoryEloquent.
 *
 * @package namespace Juzaweb\Core\Repositories;
 */
class PostRepositoryEloquent extends BaseRepository implements PostRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Post::class;
    }

    public function getSetting()
    {
        return PostType::getPostTypes('posts');
    }
}
