<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Enums;

enum PostStatus: string
{
    case PUBLISHED = 'published';
    case PRIVATE = 'private';
    case DRAFT = 'draft';

    public static function all(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::PUBLISHED => __('core::translation.published'),
            self::DRAFT => __('core::translation.draft'),
            self::PRIVATE => __('core::translation.private'),
        };
    }
}
