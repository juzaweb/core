<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    larabizcom/larabiz
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Core\Traits;

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
     * @return string
     */
    public static function generateCode(): string
    {
        // keep generating a new code until we find one that doesn't already exist
        do {
            $code = Str::random(15);
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
                    $model->setAttribute('code', static::generateCode());
                }
            }
        );
    }
}
