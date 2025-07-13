<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Models\Enums;

enum PageStatus: string
{
    case PUBLISHED = 'published';
    case DRAFT = 'draft';

    public static function all(): array
    {
        return [
            self::PUBLISHED->value => __('Published'),
            self::DRAFT->value => __('Draft'),
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
