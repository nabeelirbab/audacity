<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Jobs\PerformanceStatCalculation;
use App\Models\Performance;

class CalculatePerformanceStat extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:calc_stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate Performance Stat';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $performances = Performance
                ::active()
                ->get(['id']);

            foreach($performances as $performance) {
                PerformanceStatCalculation::dispatch($performance->id)->onQueue('performances');
            }

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();

            throw $e;
        }
    }
}
