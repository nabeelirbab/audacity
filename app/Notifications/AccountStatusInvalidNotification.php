<?php

namespace App\Notifications;

use App\Mail\AccountInvalidMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AccountStatusInvalidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Account $account;
    public int $manager_id;

    public function __construct(Account $account, int $managerId)
    {
        $this->account = $account;
        $this->manager_id = $managerId;

        $this->onQueue('default');
    }

    public function via($notifiable)
    {
        return ['database', ManagerMailer::class];
    }

    public static function getIcon() {
        return 'fa fa-user-circle';
    }

    public function toDatabase($notifiable) {
        return ['message' => __('notification.user.account_invalid',
            [
                'account_number' => $this->account->account_number,
                'broker_server' => $this->account->broker_server_name
            ]
        )];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return (
            new AccountInvalidMail(
                $this->account->account_number,
                $this->account->broker_server_name,
                $this->manager_id
            )
        );
    }

}
