<?php

namespace App\Notifications;

use App\Mail\UserActivatedMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\User;
use App\UserManagerNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserActivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $manager_id;
    public User $user;

    public function __construct(User $user, int $managerId)
    {
        $this->manager_id = $managerId;
        $this->user = $user;

        $this->onQueue('default');
    }

    public function via($notifiable)
    {
        return [UserManagerNotifier::class, ManagerMailer::class];
    }

    public static function getIcon() {
        return 'fa fa-flag';
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return (
            new UserActivatedMail(
                    $this->user->username,
                    admin_url('/'),
                    $this->manager_id
            )
        );
    }

    public function toUserManagerNotifier($notifiable) {
        return __('notification.manager.user_activated',
        [
            'user_id' => $this->user->id,
            'email' => $this->user->username,
        ]);
    }
}