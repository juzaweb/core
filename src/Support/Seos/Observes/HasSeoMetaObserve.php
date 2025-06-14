<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
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
