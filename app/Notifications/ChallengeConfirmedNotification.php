<?php

namespace App\Notifications;

use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\Challenge;
use App\Models\PerformancePlan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Mail\ChallengeConfirmedMail;

class ChallengeConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Challenge $order;
    public PerformancePlan $plan;
    public int $manager_id;

    /**
     * Create a new notification instance.
     *
     * SendActivationEmail constructor.
     *
     * @param $token
     */
    public function __construct(Challenge $order, PerformancePlan $plan, int $manager_id)
    {
        $this->plan = $plan;
        $this->order = $order;
        $this->manager_id = $manager_id;

        $this->onQueue('performances');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [ManagerMailer::class, 'database'];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return (
            new ChallengeConfirmedMail(
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
        return ['message' => __('notification.user.order_confirmed',
            [
                'order_id' => $this->order->id,
                'plan' => $this->plan->title
            ]
        )];
    }

}
