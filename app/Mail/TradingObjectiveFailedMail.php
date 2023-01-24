<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class TradingObjectiveFailedMail extends ManagerTemplateMailable
{
    public int $account_number = 123123;
    public string $failed_objective = 'Objective1';
    public string $plan_title = 'Plan';
    public float $stat_max_loss = 10;
    public float $stat_max_daily_loss = 15;
    public float $stat_profit = 100;
    public int $stat_trading_days = 1;
    public float $target_max_loss = 10;
    public float $target_max_daily_loss = 15;
    public float $target_profit = 100;
    public int $target_min_trading_days = 1;
    public int $target_max_trading_days = 30;

    public function __construct( int $accountNumber, string $failedObjective, string $planTitle, ?float $statMaxLoss, ?float $statMaxDailyLoss,
        ?float $statProfit, ?int $statTotalDays, ?float $targetMaxLoss, ?float $targetMaxDailyLoss,
        ?float $targetProfit, ?int $targetMinTradingDays, ?int $targetMaxTradingDays, int $managerId)
    {
        $this->account_number = $accountNumber;
        $this->failed_objective = $failedObjective;
        $this->plan_title = $planTitle;
        $this->stat_max_loss = $statMaxLoss ?? 0;
        $this->stat_max_daily_loss = $statMaxDailyLoss ?? 0;
        $this->stat_profit = $statProfit ?? 0;
        $this->stat_trading_days = $statTotalDays ?? 0;
        $this->target_max_loss = $targetMaxLoss ?? 0;
        $this->target_max_daily_loss = $targetMaxDailyLoss ?? 0;
        $this->target_profit = $targetProfit ?? 0;
        $this->target_min_trading_days = $targetMinTradingDays ?? 0;
        $this->target_max_trading_days = $targetMaxTradingDays ?? 0;

        parent::__construct($managerId);
    }

}
