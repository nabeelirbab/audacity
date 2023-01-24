<?php

namespace App\Models;

use App\Models\Strategy;

use Illuminate\Database\Eloquent\Model;

class StrategyOrder extends Model
{
    protected $table = 'strategy_orders';

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }
}
