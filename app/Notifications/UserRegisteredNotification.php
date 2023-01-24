<?php

namespace App\Notifications;

use App\Models\User;
use App\UserManagerNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UserRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {

        $this->onQueue('default');
    }

    public function via($notifiable)
    {
        return [UserManagerNotifier::class];
    }

    public static function getIcon() {
        return 'fa fa-plus';
    }

    public function toUserManagerNotifier($notifiable) {
        return __('notification.manager.user_registered',
        [
            'user_id' => $notifiable->id,
            'email' => $notifiable->email,
        ]);
    }
}