<?php

namespace App\Notifications;

use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\Challenge;
use App\Models\PerformancePlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Mail\ChallengeCancelledMail;
use App\UserManagerNotifier;

class ChallengeCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Challenge $order;
    public PerformancePlan $plan;
    public int $manager_id;
    private bool $notifyManager;

    public function __construct(Challenge $order, PerformancePlan $plan, int $manager_id, bool $notifyManager = false)
    {
        $this->plan = $plan;
        $this->order = $order;
        $this->manager_id = $manager_id;
        $this->notifyManager = $notifyManager;

        $this->onQueue('performances');
    }

    public function via($notifiable)
    {
        $t =  [ManagerMailer::class, 'database'];
        if($this->notifyManager)
            $t[] = UserManagerNotifier::class;
        return $t;
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return (
            new ChallengeCancelledMail(
                $this->plan->title,
                $this->order->id,
                $this->manager_id
            )
        );
    }

    public static function getIcon() {
        return 'fa fa-clipboard';
    }

    public function toDatabase($notifiable) {
        return ['message' => __('notification.user.order_cancelled',
            [
                'order_id' => $this->order->id,
                'plan' => $this->plan->title
            ]
        )];
    }

    public function toUserManagerNotifier($notifiable) {
        return __('notification.manager.order_cancelled',
        [
            'order_id' => $this->order->id,
            'plan' => $this->plan->title,
        ]);
    }

}
