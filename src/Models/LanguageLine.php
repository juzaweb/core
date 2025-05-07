<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Models;

use Spatie\TranslationLoader\LanguageLine as BaseLanguageLine;

/**
 * Juzaweb\Core\Models\LanguageLine
 *
 * @property int $id
 * @property string $namespace
 * @property string $group
 * @property string $key
 * @property array $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine query()
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine whereNamespace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LanguageLine whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LanguageLine extends BaseLanguageLine
{
    //
}
