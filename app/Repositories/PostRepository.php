<?php

namespace Juzaweb\Core\Repositories;

use Juzaweb\Core\Repository\Contracts\RepositoryInterface;

/**
 * Interface PostRepository.
 *
 * @package namespace Juzaweb\Core\Repositories;
 */
interface PostRepository extends RepositoryInterface
{
    public function getSetting();
}
