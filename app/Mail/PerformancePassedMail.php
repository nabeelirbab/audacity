<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class PerformancePassedMail extends ManagerTemplateMailable
{
    public int $account_number = 123123;
    public string $plan_title = 'Plan';
    public float $stat_max_loss = 10;
    public float $stat_max_daily_loss = 15;
    public float $stat_profit = 100;
    public int $stat_trading_days = 1;
    public float $target_max_loss = 100;
    public float $target_max_daily_loss = 15;
    public float $target_profit = 100;
    public int $target_min_trading_days = 1;
    public int $target_max_trading_days = 30;

    public function __construct( int $accountNumber, string $planTitle, float $statMaxLoss, float $statMaxDailyLoss,
        float $statProfit, int $statTotalDays, float $targetMaxLoss, float $targetMaxDailyLoss,
        float $targetProfit, int $targetMinTradingDays, int $targetMaxTradingDays, int $managerId)
    {
        $this->account_number = $accountNumber;
        $this->plan_title = $planTitle;
        $this->stat_max_loss = $statMaxLoss;
        $this->stat_max_daily_loss = $statMaxDailyLoss;
        $this->stat_profit = $statProfit;
        $this->stat_trading_days = $statTotalDays;
        $this->target_max_loss = $targetMaxLoss;
        $this->target_max_daily_loss = $targetMaxDailyLoss;
        $this->target_profit = $targetProfit;
        $this->target_min_trading_days = $targetMinTradingDays;
        $this->target_max_trading_days = $targetMaxTradingDays;

        parent::__construct($managerId);
    }

}
