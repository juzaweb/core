<?php

namespace Juzaweb\Modules\Core\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Juzaweb\Modules\Admin\Models\Website;
use Juzaweb\Modules\Admin\Networks\Facades\Network;

class ResetPassword extends BaseResetPassword implements ShouldQueue
{
    use Queueable;

    protected Website $website;

    public function __construct($token, Website $website)
    {
        parent::__construct($token);

        $this->website = $website;
    }

    public function toMail($notifiable)
    {
        Network::init($this->website);

        return parent::toMail($notifiable);
    }
}
