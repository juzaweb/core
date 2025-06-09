<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://larabiz.com
 */

namespace Juzaweb\Core\Notifications\Contracts;

interface Notification
{
    public function subscriptable(string $channel, array $data = []): void;

    public function getSubscriptableChannels(): array;

    public function getSubscriptableData(string $channel): array;
}
