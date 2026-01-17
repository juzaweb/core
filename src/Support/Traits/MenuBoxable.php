<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support\Traits;

use Illuminate\Database\Eloquent\Builder;

trait MenuBoxable
{
    abstract public function scopeWhereInMenuBox(Builder $builder): Builder;

    abstract public function getEditUrl(): string;
}
