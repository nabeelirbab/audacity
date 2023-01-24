<?php

namespace App\Jobs;

use App\Jobs\ShouldQueueBase;
use App\Models\PerformanceStat;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;

final class PerformanceStatCalculation extends ShouldQueueBase implements ShouldBeUniqueUntilProcessing
{
    private $performanceId;

    protected $signature = 'perfomance:calc_stat';

    public $timeout = 120;
    public $tries = 5;

    public function uniqueId()
    {
        return $this->performanceId;
    }

    public function __construct(int $id)
    {
        $this->performanceId = $id;
    }

    public function handle()
    {
        $performanceStat = PerformanceStat::find($this->performanceId);

        !is_null($performanceStat) && $performanceStat->calculate();
    }

    public function failed($exception)
    {
        $this->critical($exception);
    }

    public function tags()
    {
        return ['performances', 'performance:'.$this->performanceId];
    }
}
