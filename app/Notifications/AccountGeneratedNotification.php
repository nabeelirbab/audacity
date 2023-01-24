<?php

namespace App\Notifications;

use App\Mail\AccountGeneratedMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\Account;
use App\UserManagerNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AccountGeneratedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Account $account;
    public int $manager_id;

    public function __construct(Account $account, int $manager_id)
    {
        $this->account = $account;
        $this->manager_id = $manager_id;

        $this->onQueue('default');
    }

    public function via($notifiable)
    {
        return [ManagerMailer::class, 'database', UserManagerNotifier::class];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return (
            new AccountGeneratedMail(
                $notifiable->name,
                $this->account->account_number,
                $this->account->password,
                $this->account->broker_server_name,
                $this->manager_id
            )
        );
    }

    public static function getIcon() {
        return 'fa fa-user-circle';
    }

    public function toUserManagerNotifier($notifiable) {
        return __('notification.manager.order_account_generated', ['account_number' => $this->account->account_number]);
    }

    public function toDatabase($notifiable) {
        return ['message' => __('notification.user.order_account_generated',
            [
                'account_number' => $this->account->account_number,
                'password' => $this->account->password,
                'server' => $this->account->broker_server_name,
            ]
        )];
    }

}