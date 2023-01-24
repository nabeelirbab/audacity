<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderWithCopied extends Order
{
    protected $appends = [
        'countCopiedClosed',
        'countCopiedOpen',
        'countCopiedNotFilled',
    ];

    public function getCountCopiedClosedAttribute() {

        return DB::table($this->table)
            ->selectRaw('count(ticket) as cnt')
            ->where('magic', $this->ticket)
            ->where('status', OrderStatus::CLOSED)
            ->value('cnt');
    }

    public function getCountCopiedOpenAttribute() {

        return DB::table($this->table)
            ->selectRaw('count(ticket) as cnt')
            ->where('magic', $this->ticket)
            ->where('status', OrderStatus::OPEN)
            ->value('cnt');
    }

    public function getCountCopiedNotFilledAttribute() {

        return DB::table($this->table)
            ->selectRaw('count(ticket) as cnt')
            ->where('magic', $this->ticket)
            ->where('status', OrderStatus::NOT_FILLED)
            ->value('cnt');
    }

}
