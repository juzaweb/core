<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Enums;

enum PageStatus: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';

    public static function all(): array
    {
        return [
            self::PUBLISHED->value => __('core::translation.published'),
            self::DRAFT->value => __('core::translation.draft'),
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => __('core::translation.draft'),
            self::PUBLISHED => __('core::translation.published'),
        };
    }
}
