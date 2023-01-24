<?php

namespace App\Events;

use App\Enums\OrderState;
use App\Repositories\MT4OrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderStateChanged implements ShouldQueue
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

}
