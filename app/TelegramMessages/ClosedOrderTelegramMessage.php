<?php

namespace App\TelegramMessages;

use App\Repositories\MT4OrderRepository;

class ClosedOrderTelegramMessage extends ManagerTelegramMessage {

    public string $symbol;
    public string $title;
    public string $type;
    public int $ticket;

    public function __construct(MT4OrderRepository $order, int $manager_id,
        string $title) {

        $this->ticket = $order->ticket;
        $this->symbol = $order->symbol;
        $this->type = $order->type;
        $this->title = $title;

        parent::__construct($manager_id);
    }
}