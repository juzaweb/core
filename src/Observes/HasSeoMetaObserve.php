<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Observes;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Modules\Core\Traits\HasSeoMeta;

class HasSeoMetaObserve
{
    /**
     * @param  Model|HasSeoMeta  $model
     * @return void
     */
    public function saved(Model $model)
    {
        $model->seoMeta()->updateOrCreate([], $model->seoMetaFill());
    }

    /**
     * @param  Model|HasSeoMeta  $model
     * @return void
     */
    public function deleting(Model $model)
    {
        $model->seoMeta()->delete();
    }
}
