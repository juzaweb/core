<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Juzaweb\Core\Support\Fields;

/**
 * @see \Juzaweb\Core\Support\FieldFactory
 */
interface Field
{
    public function text(string|Model $label, string $name, array $options = []): Fields\Text;
}
