<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/laravel-cms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://juzaweb.com/cms
 * @license    MIT
 */

namespace Juzaweb\Core\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Juzaweb\Core\Traits\HasTablePrefix;

class Model extends EloquentModel
{
    use HasTablePrefix;


}