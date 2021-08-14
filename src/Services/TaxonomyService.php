<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by JUZAWEB.
 * Date: 5/31/2021
 * Time: 10:20 PM
 */

namespace Juzaweb\Core\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Juzaweb\Core\Repositories\TaxonomyRepository;

class TaxonomyService
{
    protected $taxonomyRepository;

    public function __construct(TaxonomyRepository $taxonomyRepository)
    {
        $this->taxonomyRepository = $taxonomyRepository;
    }

    public function create(array $attributes)
    {
        $this->validate($attributes);
        DB::beginTransaction();
        try {
            $model = $this->taxonomyRepository->create($attributes);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $model;
    }

    public function update(array $attributes, $id)
    {
        $this->validate($attributes);
        DB::beginTransaction();
        try {
            $model = $this->taxonomyRepository->update($attributes, $id);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return $model;
    }

    public function delete($id)
    {
        $ids = is_array($id) ? $id : [$id];
        foreach ($ids as $id) {
            try {
                DB::beginTransaction();
                $this->taxonomyRepository->delete($id);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return true;
    }

    protected function validate($attributes)
    {
        $validator = Validator::make($attributes, [
            'name' => 'required|string|max:250',
            'thumbnail' => 'nullable|string|max:150',
        ]);

        $validator->validate();
    }
}