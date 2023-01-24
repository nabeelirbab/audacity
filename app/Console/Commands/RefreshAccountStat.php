<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Enums\AccountStatus;
use App\Jobs\AccountStatCalculation;
use App\Models\AccountStat;
//use Illuminate\Support\Facades\Queue;

class RefreshAccountStat extends BaseCommand
{
    protected $signature = 'account:refresh_stat';

    protected $description = 'Refresh Accounts Stat';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {

            // if(Queue::size('accounts_stat') > 0)
            //         return;

            $stats = AccountStat
                ::with('account:account_number,account_status')
                ->whereHas('account',
                    function($q) {
                        return $q->whereIn('account_status', [AccountStatus::ONLINE]);
                    })
                ->where(function($query) {
                    $query->whereRaw('nof_processed != nof_closed')->orWhere('deposit', 0)->orWhereNull('deposit');
                })
                ->get(['account_number']);

            foreach($stats as $stat) {

                if($stat->isAllOrdersUploaded() == false) {
                    continue;
                }

                AccountStatCalculation::dispatch($stat->account_number)->onQueue('accounts_stat');
            }

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();
        }
    }
}
