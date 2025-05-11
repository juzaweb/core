<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Support;

use Illuminate\Database\Eloquent\Model;

class FieldFactory
{
    public function text(string|Model $label, string $name, array $options = []): Fields\Text
    {
        return new Fields\Text($label, $name, $options);
    }
}
