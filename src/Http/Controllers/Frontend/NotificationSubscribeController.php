<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://cms.juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Modules\Core\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Juzaweb\Modules\Admin\Models\Guest;
use Juzaweb\Modules\Core\Http\Controllers\ThemeController;
use Juzaweb\Modules\Core\Http\Requests\NotificationSubscribeRequest;
use Juzaweb\Modules\Core\Models\NotificationSubscription;
use Juzaweb\Modules\Notification\Mail\SubscriptionVerifyEmail;

class NotificationSubscribeController extends ThemeController
{
    public function subscribe(NotificationSubscribeRequest $request, string $channel)
    {
        if (! in_array($channel, ['mail', 'fcm'])) {
            return $this->error(__('core::translation.channel_not_supported'));
        }

        $notifiable = $request->user() ?? Guest::firstOrCreate(
            [
                'ipv4' => client_ip(),
            ],
            [
                'user_agent' => $request->userAgent(),
            ]
        );

        $name = $channel === 'mail' ? 'email' : 'token';
        $data = $request->input($name);

        if ($channel === 'mail') {
            $url = URL::temporarySignedRoute(
                'notification.verify',
                now()->addMinutes(60),
                [
                    'channel' => $channel,
                    'notifiable_type' => get_class($notifiable),
                    'notifiable_id' => $notifiable->id,
                    'data' => $data,
                ]
            );

            Notification::route('mail', $data)->notify(new SubscriptionVerifyEmail($url));

            return $this->success(__('core::translation.verification_email_sent'));
        }

        $this->doSubscribe($channel, $notifiable, $name, $data);

        return $this->success(
            [
                'message' => __('core::translation.subscribed_successfully'),
            ]
        );
    }

    public function verify(Request $request, string $channel)
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $notifiableType = $request->query('notifiable_type');
        $notifiableId = $request->query('notifiable_id');
        $data = $request->query('data');

        $notifiable = $notifiableType::find($notifiableId);

        if (! $notifiable) {
            return $this->error(__('core::translation.notifiable_not_found'));
        }

        $name = $channel === 'mail' ? 'email' : 'token';

        $this->doSubscribe($channel, $notifiable, $name, $data);

        return view('core::frontend.subscription-verified', [
            'title' => __('core::translation.subscription_verified_successfully'),
        ]);
    }

    protected function doSubscribe($channel, $notifiable, $name, $data)
    {
        $subscription = NotificationSubscription::firstOrCreate([
            'channel' => $channel,
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
        ], [
            'data' => [[$name => $data]],
        ]);

        if (! $subscription->wasRecentlyCreated) {
            $allData = $subscription->data;
            if (! collect($allData)->contains($name, $data)) {
                $allData[] = [$name => $data];
                $subscription->data = $allData;
                $subscription->save();
            }
        }

        return $subscription;
    }
}
