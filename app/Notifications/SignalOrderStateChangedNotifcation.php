<?php

namespace App\Notifications;

use App\Enums\OrderState;
use App\Repositories\MT4OrderRepository;
use App\TelegramMessages\ClosedOrderTelegramMessage;
use App\TelegramMessages\OpenOrderTelegramMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class SignalOrderStateChangedNotifcation extends Notification implements ShouldQueue
{
    use Queueable;

    public MT4OrderRepository $order;
    public OrderState $state;

    public function __construct(MT4OrderRepository $order, OrderState $state)
    {
        $this->order = $order;
        $this->state = $state;

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
        if($notifiable->telegram_enabled)
            return [TelegramChannel::class];

        return [];
    }

    public function toTelegram($notifiable) : TelegramMessage
    {
        if($this->state == OrderState::OPENED)
            return (new OpenOrderTelegramMessage( $this->order, $notifiable->manager_id, $notifiable->title));

        return (new ClosedOrderTelegramMessage( $this->order, $notifiable->manager_id, $notifiable->title));
    }
}