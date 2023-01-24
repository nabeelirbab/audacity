<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Enums\PerformanceStatus;
use App\Jobs\PerformanceStatCalculation;
use App\Models\Performance;

class CaclulatePerformanceTarget extends BaseCommand
{
    protected $signature = 'performance:calc_target';

    protected $description = 'Calculate Performance Target';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $performances = Performance
                ::with(['account_stat', 'plan'])
                ->whereHas('account_stat',
                    function($account) {
                        return $account->deposited();
                    })
                ->whereStatus(PerformanceStatus::CALCULATING)
                ->get();

            foreach ($performances as $performance) {

                $target = $performance->target;

                if(is_null($target))
                    continue;

                $target->max_daily_loss = -$performance->plan->max_daily_loss_perc/100.0*$performance->account_stat->deposit;
                $target->max_loss = -$performance->plan->max_loss_perc/100.0*$performance->account_stat->deposit;
                $target->profit = $performance->plan->profit_perc/100.0*$performance->account_stat->deposit;

                $performance->status = PerformanceStatus::ACTIVE;
                $performance->save();

                $target->save();

                PerformanceStatCalculation::dispatch($performance->id)->onQueue('performances');
            }

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();
        }
    }
}