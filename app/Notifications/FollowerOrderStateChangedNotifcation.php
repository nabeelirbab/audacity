<?php

namespace App\Notifications;

use App\Enums\OrderState;
use App\Mail\ClosedOrderSignalEmail;
use App\Mail\OpenOrderSignalEmail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Repositories\MT4OrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class FollowerOrderStateChangedNotifcation extends Notification implements ShouldQueue
{
    use Queueable;

    public MT4OrderRepository $order;
    public int $manager_id;
    public OrderState $state;
    public string $title;

    /**
     * Create a new notification instance.
     *
     * SendActivationEmail constructor.
     *
     * @param $token
     */
    public function __construct(MT4OrderRepository $order, OrderState $state, $manager_id, string $title)
    {
        $this->order = $order;
        $this->state = $state;
        $this->manager_id = $manager_id;
        $this->title = $title;

        $this->onQueue('orders');
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
        if($notifiable->email_enabled)
            return [ManagerMailer::class];

        return [];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        if($this->state == OrderState::OPENED)
            return (new OpenOrderSignalEmail( $this->order, $this->manager_id, $this->title));

        return (new ClosedOrderSignalEmail( $this->order, $this->manager_id, $this->title));
    }

}
