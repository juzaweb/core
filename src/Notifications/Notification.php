<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Modules\Core\Notifications;

interface Notification
{
    public function subscriptable(string $channel, array $data = []): void;

    public function getSubscriptableChannels(): array;

    public function getSubscriptableData(string $channel): array;
}
