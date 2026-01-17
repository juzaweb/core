<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/laravel-translations
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Translations\Enums;

enum TranslateHistoryStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';

    /**
     * Get all statuses as an array.
     *
     * @return array
     */
    public function all(): array
    {
        return [
            self::PENDING->value => __('translation::translation.pending'),
            self::SUCCESS->value => __('translation::translation.success'),
            self::FAILED->value => __('translation::translation.failed'),
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => __('translation::translation.pending'),
            self::SUCCESS => __('translation::translation.success'),
            self::FAILED => __('translation::translation.failed'),
        };
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isSuccess(): bool
    {
        return $this === self::SUCCESS;
    }

    public function isFailed(): bool
    {
        return $this === self::FAILED;
    }
}
