<?php

namespace App\Repositories;

class MT4OrderRepository
{
    public string $symbol;
    public int $account_id;
    public int $ticket;
    public string $type;
    public string $price_open;
    public string $pl;
    public string $pl_pips;
    public int $seconds_ago;

    public function __construct(array $data)
    {
        $this->symbol = $data['symbol'];
        $this->account_id = $data['account_id'];
        $this->ticket = $data['ticket'];
        $this->type = $data['type'];
        $this->seconds_ago = $data['seconds_ago'];

        isset($data['price_open']) && $this->price_open = $data['price_open'];
        isset($data['pl']) && $this->pl = $data['pl'];
        isset($data['pl_pips']) && $this->pl = $data['pl_pips'];

    }

    public function toArray() : array {
        return (array)$this;
    }
}
