<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 */

namespace Juzaweb\Core\Notifications;

use Juzaweb\Core\Notifications\Contracts\Notification;

class NotificationFactory implements Notification
{
    /**
     * @var array <string, array<string, mixed>>
     */
    protected array $subscriptableChannels = [];

    /**
     * @param string $channel
     * @param array<string, mixed> $options
     * @return void
     */
    public function subscriptable(string $channel, array $data = []): void
    {
        $this->subscriptableChannels[$channel] = $data;
    }

    /**
     * Get subscriptable channels.
     *
     * @return array<string>
     */
    public function getSubscriptableChannels(): array
    {
        return array_keys($this->subscriptableChannels);
    }

    /**
     * Get subscriptable data.
     *
     * @param string $channel
     * @return array<string, mixed>
     */
    public function getSubscriptableData(string $channel): array
    {
        return $this->subscriptableChannels[$channel] ?? [];
    }
}
