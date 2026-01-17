<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Model
 */
trait HasCode
{
    /**
     * Generate a unique code
     *
     * @param  int  $length
     * @return string
     */
    public static function generateCode(int $length = 16): string
    {
        // keep generating a new code until we find one that doesn't already exist
        do {
            $code = Str::random($length);
        } while (static::withoutGlobalScopes()->where('code', $code)->exists());

        return $code;
    }

    protected static function bootHasCode(): void
    {
        /**
         * Listen for the creating event on the user model.
         * Sets the 'id' to a UUID using Str::uuid() on the instance being created
         */
        static::creating(
            function ($model) {
                if (!$model->getAttribute('code')) {
                    $model->setAttribute('code', static::generateCode($model->getCodeLength()));
                }
            }
        );
    }

    protected function getCodeLength(): int
    {
        return 16; // Default code length, can be overridden in the model
    }
}
