<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AccountEquity extends Model
{
    protected $table = 'account_equities';
    protected $primaryKey = 'time';

    private float $minEquity = 0;
    private float $minDailyEquity = 0;
    private $minEquityAt;
    private $minDailyEquityAt;
    private float $profitCurrent = 0;
    private bool $hasHedging = false;
    private bool $slNotUsed = false;

    private $hasHedgingAt = null;
    private $slNotUsedAt = null;

    public function calc(string $brokerName, int $accountNumber) : bool {

        $fromWhere = " from `{$this->table}` where `broker_name`=\"$brokerName\" and `account_number`=$accountNumber";

        $profit = $this
            ::where('broker_name', $brokerName)
            ->where('account_number', $accountNumber)
            ->orderBy('time', 'desc')
            ->first();

        if(!is_null($profit)) {
            $this->profitCurrent = $profit->profit;
        }

        $hasHedging = $this
            ::where('broker_name', $brokerName)
            ->where('account_number', $accountNumber)
            ->orderBy('time', 'desc')
            ->where('has_hedging', 1)
            ->first();

        if(!is_null($hasHedging)) {
            $this->hasHedging = true;
            $this->hasHedgingAt = Carbon::createFromTimestampUTC($hasHedging->time);
        }

        $slNotUsed = $this
            ::where('broker_name', $brokerName)
            ->where('account_number', $accountNumber)
            ->orderBy('time', 'desc')
            ->where('sl_not_used', 1)->first();


        if(!is_null($slNotUsed)) {
            $this->slNotUsed = true;
            $this->slNotUsedAt = Carbon::createFromTimestampUTC($slNotUsed->time);
        }

        $minsToday = $this
            ::whereRaw("gain_today=(select min(gain_today) $fromWhere )")
            ->where('broker_name', $brokerName)
            ->where('account_number', $accountNumber)
            ->first();

        if(!is_null($minsToday)) {
            $this->minDailyEquity = min($minsToday->gain_today, 0);
            if($this->minDailyEquity < 0)
                $this->minDailyEquityAt = Carbon::createFromTimestampUTC($minsToday->time);
        }

        $mins = $this
            ::whereRaw("gain=(select min(gain) $fromWhere )")
            ->where('broker_name', $brokerName)
            ->where('account_number', $accountNumber)
            ->first();

        if(!is_null($mins)) {
            $this->minEquity = min($mins->gain, 0);
            if($this->minEquity < 0)
                $this->minEquityAt = Carbon::createFromTimestampUTC($mins->time);
        }

        return true;
    }

    public function getProfit() {
        return $this->profitCurrent;
    }

    public function getMinEquity() {
        return $this->minEquity;
    }

    public function getMinDialyEquity() {
        return $this->minDailyEquity;
    }

    public function getMinEquityAt() {
        return $this->minEquityAt;
    }

    public function getMinDialyEquityAt() {
        return $this->minDailyEquityAt;
    }

    public function getHasHedging() {
        return $this->hasHedging;
    }

    public function getHasHedgingAt() {
        return $this->hasHedgingAt;
    }

    public function getSlNotUsed() {
        return $this->slNotUsed;
    }

    public function getSlNotUsedAt() {
        return $this->slNotUsedAt;
    }
}
