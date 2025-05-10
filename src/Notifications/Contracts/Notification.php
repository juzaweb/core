<?php
/**
 * LARABIZ CMS - Full SPA Laravel CMS
 *
 * @package    larabizcms/larabiz
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
