<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Helpers\MT4Commands;
use App\Enums\MT4RunResultType;
use App\Enums\AccountStatus;
use App\Console\Commands\BaseCommand;

class StopMovedAccounts extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:stopmoved';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop account which are moved to another API Server';

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

        $accounts = Account::with('broker_server')
            ->whereHas('broker_server', static function ($query) {
                $query->api();
            })
            ->whereNotNull('old_api_server_ip')
            //->where('account_status', '!=', AccountStatus::PENDING)
            ->get();

        foreach ($accounts as $account) {

            if(empty($account->old_api_server_ip) || $account->old_api_server_ip == $account->api_server_ip) {
                $account->old_api_server_ip = null;
                $account->save();

                continue;
            }
            $this->info('Stopping moved account...', [
                'acc' => $account->account_number,
                'old_ip' => $account->old_api_server_ip,
                'curr_ip' => $account->api_server_ip
            ]);
            $ret = MT4Commands::stop(
                $account->old_api_server_ip,
                $account->broker_server_name,
                $account->account_number,
                $account->password
            );

            $code = $ret->getCode();

            if ($code & MT4RunResultType::FAILED_REPEATABLE) {
                $account->last_error = $ret->getMessage();

                $this->warning('Failed to stop moved account, repeating', [
                    'acc' => $account->account_number,
                    'msg' => $ret->getMessage()
                ]);
                continue;
            }

            $account->old_api_server_ip = null;
            $account->save();
            $this->info('Accont is stopped.', [
                'acc' => $account->account_number,
                'old_ip' => $account->old_api_server_ip,
                'curr_ip' => $account->api_server_ip
            ]);
        }
    }
}
