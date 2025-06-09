<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasCodeWithMonth
{
    public static function generateCode(): string
    {
        $year = date('y');
        $month = date('m');

        $orderPrefix = static::$codePrefix ?? '';
        $minNumberOrderCode = static::$minNumberOrderCode ?? 4;
        $nowOrder = self::whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();

        do {
            $nowOrder++;

            if (strlen($nowOrder) < $minNumberOrderCode) {
                $nowOrder = str_pad($nowOrder, $minNumberOrderCode, '0', STR_PAD_LEFT);
            }

            $code = $orderPrefix . $year . $month . $nowOrder;
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Boot the HasCodeWithMonth trait for a model.
     *
     * When creating the model, if the code attribute is not set,
     * generate a unique code using the generateCode method.
     */
    protected static function bootHasCodeWithMonth(): void
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
