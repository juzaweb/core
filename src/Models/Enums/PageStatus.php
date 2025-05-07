<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Models\Enums;

enum PageStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public static function options(): array
    {
        return [
            self::DRAFT->value => __('Draft'),
            self::PUBLISHED->value => __('Published'),
        ];
    }

    public static function all(): array
    {
        return [
            self::DRAFT->value,
            self::PUBLISHED->value,
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::PUBLISHED => __('Published'),
        };
    }
}
