<?php

namespace App\Notifications;

use App\Mail\NewChallengeMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\Challenge;
use App\Models\PerformancePlan;
use App\UserManagerNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ChallengeCreatedNotifcation extends Notification implements ShouldQueue
{
    use Queueable;

    public Challenge $order;
    public PerformancePlan $plan;
    public int $manager_id;

    public function __construct(Challenge $order, PerformancePlan $plan, int $manager_id)
    {
        $this->plan = $plan;
        $this->order = $order;
        $this->manager_id = $manager_id;

        $this->onQueue('performances');
    }

    public function via($notifiable)
    {
        return [ManagerMailer::class, 'database', UserManagerNotifier::class];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return (
            new NewChallengeMail(
                $this->order->id,
                $this->plan->title,
                $this->plan->max_daily_loss_perc,
                $this->plan->max_loss_perc,
                $this->plan->min_trading_days,
                $this->plan->max_trading_days,
                $this->plan->profit_perc,
                $this->manager_id
            )
        );
    }

    public static function getIcon() {
        return 'fa fa-clipboard';
    }

    public function toUserManagerNotifier($notifiable) {
        return __('notifications.manager.order_created',
        [
            'order_id' => $this->order->id,
            'plan' => $this->plan->title,
        ]);
    }

    public function toDatabase($notifiable) {
        return ['message' => __('notification.user.order_created',
            [
                'order_id' => $this->order->id,
                'plan' => $this->plan->title
            ]
        )];
    }

}
