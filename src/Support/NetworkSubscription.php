<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Support;

use Juzaweb\Modules\Subscription\Contracts\SubscriptionModule;
use Juzaweb\Modules\Subscription\Entities\SubscriptionResult;
use Juzaweb\Modules\Subscription\Models\SubscriptionHistory;

class NetworkSubscription implements SubscriptionModule
{
    protected string $name = 'Network';

    protected string $serviceName = 'Website Premium';

    public function onSuccess(SubscriptionResult $result, array $params = []): void
    {
        info('Payment success', [
            'subscription_history_id' => $result->getSubscriptionHistory()->id,
            'params' => $params,
        ]);
    }

    public function onCancel(SubscriptionHistory $result, array $params = [])
    {
        // Handle payment cancellation
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function getServiceDescription(): string
    {
        return 'Subscription Website Premium on Juzaweb';
    }

    public function getReturnUrl(): string
    {
        return admin_url('upgrade');
    }
}
