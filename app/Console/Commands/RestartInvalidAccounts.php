<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Models\Account;
use App\Enums\AccountStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RestartInvalidAccounts extends BaseCommand
{
    protected $signature = 'accounts:restart_invalid';

    protected $description = 'Restart Invalid accounts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $diff = 30; //minutes
        $max_tries = 5; //times

        try {

  //          DB::enableQueryLog();
            $accounts = Account
                ::whereIn('account_status', [AccountStatus::INVALID, AccountStatus::INVALID_STOPPED])
                ->where('updated_at','<',Carbon::now()->subMinutes($diff))
                ->where('count_invalid_restarts', '<', $max_tries)
                ->get();

            foreach($accounts as $account) {
                $account->restart();
                $account->update([
                    'count_invalid_restarts' => DB::raw('count_invalid_restarts+1')
                ]);
            }

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();
        }
    }
}
