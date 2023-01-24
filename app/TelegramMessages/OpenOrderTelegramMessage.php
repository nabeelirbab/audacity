<?php

namespace App\TelegramMessages;

use App\Repositories\MT4OrderRepository;

class OpenOrderTelegramMessage extends ManagerTelegramMessage {

    public string $symbol;
    public string $title;
    public string $type;
    public int $ticket;

    public function __construct(MT4OrderRepository $order, int $manager_id, string $title) {

        $this->symbol = $order->symbol;
        $this->type = $order->type;
        $this->ticket = $order->ticket;

        $this->title = $title;

        parent::__construct($manager_id);
    }
}