<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Models\Performance;
use App\Models\PerformanceStat;
use Carbon\Carbon;

class CheckPerformanceStarted extends BaseCommand
{
    protected $signature = 'performance:check_started';

    protected $description = 'Check Performance Started trading';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $performances = Performance
                ::whereNull('started_at')
                ->get(['id','account_number']);

            foreach ($performances as $performance) {
                /** @var Performance $performance */

                if($order = PerformanceStat::getFirstOrder($performance->account_number)) {
                    $performance->started_at = $order->time_open;
                    $performance->save();
                }
            }

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();
        }
    }
}