<?php

namespace Juzaweb\Core\Repositories;

use Juzaweb\Core;
use Juzaweb\Core\Repository\Eloquent\BaseRepository;
use Juzaweb\Core\Models\Taxonomy;

class TaxonomyRepositoryEloquent extends BaseRepository implements TaxonomyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Taxonomy::class;
    }


}
