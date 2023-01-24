<?php

namespace App\Models;

use App\Models\AccountEquity;
use App\Models\Order;
use App\Models\Performance;
use Carbon\CarbonPeriod;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class PerformanceStat extends Model
{
    use HasDateTimeFormatter;
    use HasRelationships;

    const KEY = 'performance_id';
    const TABLE = 'performance_stat';

    protected $primaryKey = self::KEY;

    protected $table = self::TABLE;

    public function performance()
    {
        return $this->belongsTo(Performance::class, 'performance_id');
    }

    public function account()
    {
        return $this->hasOneThrough(Account::class, Performance::class, 'id', 'id', 'performance_id', 'account_id');
    }

    public static function getFirstOrder($accountNumber) {
        return Order
            ::where('account_number', $accountNumber)
            ->market()
            ->orderBy('time_open')
            ->first();
    }

    protected function calculateTradedDays($accountNumber) : int
    {
        $orderDates = Order::where('account_number', $accountNumber)
            ->marketClosed()
            ->selectRaw('DATE(time_close) close, DATE(time_open) open')
            ->get(['close','open']);

        $arr = array();
        foreach($orderDates as $date) {

            /** @var mixed $date */
            $dateRange = CarbonPeriod::create($date->close, $date->open);

            $arr = array_unique (array_merge ($arr, $dateRange->toArray()));
        }

        return count($arr);
    }

    public function calculate() : bool
    {
        $isValid = false;
        $account = $this->account()->first(['broker_server_name', 'accounts.account_number', 'accounts.id']);

        if(is_null($account))
            return false;

        $a = new AccountEquity();
        if($a->calc($account->broker_server_name, $account->account_number)) {

            $isValid = true;

            $this->hedging_detected = $a->getHasHedging();
            $this->hedging_detected_at = $a->getHasHedgingAt();

            $this->sl_not_used = $a->getSlNotUsed();
            $this->sl_not_used_at = $a->getSlNotUsedAt();

            $this->max_loss_at = $a->getMinEquityAt();
            $this->max_daily_loss_at = $a->getMinDialyEquityAt();

            $this->max_daily_loss = $a->getMinDialyEquity();
            $this->max_loss = $a->getMinEquity();
            $this->profit = $a->getProfit();
        }

        $this->days_traded = $this->calculateTradedDays($account->account_number);

        $this->save();

        return $isValid;
    }

    public function reset() : void
    {
        $this->hedging_detected = 0;
        $this->hedging_detected_at = null;

        $this->sl_not_used = 0;
        $this->sl_not_used_at = null;

        $this->max_loss_at = null;
        $this->max_daily_loss_at = null;

        $this->max_daily_loss = 0;
        $this->max_loss = 0;
        $this->profit = 0;

        $this->days_traded = 0;

        $this->save();
    }

    public static function make(int $performanceId) : PerformanceStat
    {
        $s = new static();

        $s->performance_id = $performanceId;

        $s->save();

        return $s;
    }
}
