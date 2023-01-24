<?php

namespace App\Notifications;

use App\Mail\UserActivationRequiredMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserActivationRequiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $link;
    protected int $manager_id;

    public function __construct(string $link, int $managerId)
    {
        $this->link = $link;
        $this->manager_id = $managerId;
        $this->onQueue('default');
    }

    public function via($notifiable)
    {
        return [ManagerMailer::class];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return new UserActivationRequiredMail(
                $this->link,
                $this->manager_id
        );
    }

}
