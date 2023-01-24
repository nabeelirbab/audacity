<?php

namespace App\Notifications;

use App\Enums\OrderState;
use App\OrderEventsChannel;
use App\Repositories\MT4OrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\BroadcastChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AccountFollowerOrderStateChangedNotifcation extends Notification implements ShouldQueue
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
    public function __construct(MT4OrderRepository $order, OrderState $state, int $manager_id, string $accountTitle)
    {
        $this->order = $order;
        $this->state = $state;
        $this->manager_id = $manager_id;
        $this->title = $accountTitle;

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
        return [BroadcastChannel::class, OrderEventsChannel::class];
    }

    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'order' => $this->order->toArray(),
            'state' => $this->state,
            'title' => $this->title
        ]);
    }

    public function toOrderEvent($notifiable): array
    {
        return [
            'watcher_type' => get_class($notifiable),
            'watcher_id' => $notifiable->id,
            'account_id' => $this->order->account_id,
            'ticket' => $this->order->ticket,
            'state' => $this->state,
            'created_at' => now()->subSeconds($this->order->seconds_ago)
        ];
    }

}
