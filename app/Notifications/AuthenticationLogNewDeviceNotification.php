<?php

namespace App\Notifications;

use App\Mail\AuthenticationLogNewDeviceMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class AuthenticationLogNewDeviceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public AuthenticationLog $authenticationLog;

    public function __construct(AuthenticationLog $authenticationLog)
    {
        $this->authenticationLog = $authenticationLog;

        $this->onQueue('default');
    }

    public function via($notifiable)
    {
        return [ManagerMailer::class];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {

        $location = '';
        if($this->authenticationLog->location && $this->authenticationLog->location['default'] == false) {
            $country = '';
            if(isset($this->authenticationLog->location['country_name']))
                $country = $this->authenticationLog->location['country_name'];
            $city = '';
            if(isset($this->authenticationLog->location['city']))
                $city = $this->authenticationLog->location['city'];
            $location = $country.', '.$city;
        }

        return new AuthenticationLogNewDeviceMail(
                $notifiable->email,
                $this->authenticationLog->login_at->toCookieString(),
                $this->authenticationLog->ip_address,
                $this->authenticationLog->user_agent,
                $location,
                $notifiable->manager_id
        );
    }

}
