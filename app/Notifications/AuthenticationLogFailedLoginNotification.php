<?php

namespace App\Notifications;

use App\Mail\AuthenticationLogFailedLoginMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog;

class AuthenticationLogFailedLoginNotification extends Notification implements ShouldQueue
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
            $country = $this->authenticationLog->location['country_name'];
            $city = $this->authenticationLog->location['city'];
            $location = $country.', '.$city;
        }

        return new AuthenticationLogFailedLoginMail(
                $notifiable->email,
                $this->authenticationLog->login_at->toCookieString(),
                $this->authenticationLog->ip_address,
                $this->authenticationLog->user_agent,
                $location,
                $notifiable->manager_id
        );
    }

}
