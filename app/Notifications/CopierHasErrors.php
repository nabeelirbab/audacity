<?php

namespace App\Notifications;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CopierHasErrors extends Notification implements ShouldQueue
{
    use Queueable;

    protected $countErrors;

    public function __construct(int $countErrors)
    {
        $this->countErrors = $countErrors;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public static function getIcon() {
        return 'fa fa-bug';
    }

    public function toDatabase($notifiable) {
        return [
            'count' => $this->countErrors,
        ];
    }

}
