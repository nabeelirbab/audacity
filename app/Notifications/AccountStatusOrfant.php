<?php

namespace App\Notifications;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AccountStatusOrfant extends Notification implements ShouldQueue
{
    use Queueable;

    protected $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public static function getIcon() {
        return 'fa fa-user-circle';
    }

    public function toDatabase($notifiable) {
        return [
            'account_id' => $this->account->id,
            'account_number' => $this->account->account_number,
            'broker_server' => $this->account->broker_server_name
        ];
    }

}
