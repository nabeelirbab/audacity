<?php

namespace App\Mail;

use App\ManagerTemplateMailable;
use App\Repositories\MT4OrderRepository;

class OpenOrderSignalEmail extends ManagerTemplateMailable
{
    public string $symbol;
    public string $type;
    public int $ticket;
    public string $title;

    public function __construct(MT4OrderRepository $order, int $manager_id, string $title)
    {
        $this->symbol = $order->symbol;
        $this->type = $order->type;
        $this->ticket = $order->ticket;
        $this->title = $title;
        parent::__construct($manager_id);
    }

}
