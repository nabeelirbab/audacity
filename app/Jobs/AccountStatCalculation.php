<?php

namespace App\Jobs;


use App\Jobs\ShouldQueueBase;
use App\Models\AccountStat;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;

final class AccountStatCalculation extends ShouldQueueBase implements ShouldBeUniqueUntilProcessing
{
    private $accountNumber;

    protected $signature = 'account:calculate_stat';

    public $timeout = 120;
    public $tries = 5;

    public function uniqueId()
    {
        return $this->accountNumber;
    }

    public function __construct(int $accountNumber)
    {
        $this->accountNumber = $accountNumber;
    }

    public function handle()
    {
        $stat = AccountStat::find($this->accountNumber);

        if(!is_null($stat))
            $stat->refresh();
    }

    public function failed($exception)
    {
        $this->critical($exception);
    }

    public function tags()
    {
        return ['account-stat', 'account:'.$this->accountNumber];
    }
}
