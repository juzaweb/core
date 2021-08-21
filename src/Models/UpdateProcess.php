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


use Illuminate\Database\Eloquent\Model;

class UpdateProcess extends Model
{
    protected $table = 'update_processes';
    protected $fillable = [
        'name',
        'type',
        'status',
        'error'
    ];
}