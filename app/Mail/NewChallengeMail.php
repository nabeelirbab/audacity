<?php

namespace App\Mail;

use App\ManagerTemplateMailable;

class NewChallengeMail extends ManagerTemplateMailable
{
    public int $order_id = 11;
    public string $plan_title = 'Plan';
    public float $plan_max_daily_loss_perc = 10;
    public float $plan_max_loss_perc = 15;
    public float $plan_min_trading_days = 1;
    public float $plan_max_trading_days = 30;
    public float $plan_profit_perc = 100;

    public function __construct( int $orderId, string $planTitle, float $planMaxDailyLossPerc,
        float $planMaxLossPerc, int $planMinTradingDays, int $planMaxTradingDays,
        float $planProfitPerc, int $managerId)
    {
        $this->order_id = $orderId;
        $this->plan_title = $planTitle;
        $this->plan_max_daily_loss_perc = $planMaxDailyLossPerc;
        $this->plan_max_loss_perc = $planMaxLossPerc;
        $this->plan_min_trading_days = $planMinTradingDays;
        $this->plan_max_trading_days = $planMaxTradingDays;
        $this->plan_profit_perc = $planProfitPerc;

        parent::__construct($managerId);
    }
}