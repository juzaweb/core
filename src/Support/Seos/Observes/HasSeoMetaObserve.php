<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Seos\Observes;

use Illuminate\Database\Eloquent\Model;

class HasSeoMetaObserve
{
    public function saving(Model $model)
    {
        $model->seoMeta()->createOrUpdate([], $model->seoMetaFill());
    }

    public function deleting(Model $model)
    {
        $model->seoMeta()->delete();
    }
}
