<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Helpers\MT4Commands;
use App\Enums\MT4RunResultType;
use App\Enums\AccountStatus;

use App\Console\Commands\BaseCommand;

class StopDisabledAccounts extends BaseCommand
{
    protected $signature = 'accounts:stopdisabled';

    protected $description = 'Kill accounts on VPS which are invalid or suspended';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $accounts = Account::whereIn('account_status', [AccountStatus::INVALID, AccountStatus::SUSPEND])
            ->get();

        foreach ($accounts as $account) {

            /** @var Account $account */
            $this->info('Stopping account because of '. $account->account_status->label());

            $ret = MT4Commands::stop(
                $account->api_server_ip,
                $account->broker_server_name,
                $account->account_number,
                $account->password
            );

            $code = $ret->getCode();

            if ($code & MT4RunResultType::FAILED_REPEATABLE) {
                $account->last_error = $ret->getMessage();

                $this->warning('Failed to stop account, repeating', [
                    'acc' => $account->account_number,
                    'api' => $account->api_server_ip,
                    'msg' => $ret->getMessage()
                ]);
                $account->last_error = $ret->getMessage();
                $account->save();

                continue;
            }

            if ($account->account_status == AccountStatus::INVALID) {
                $account->account_status = AccountStatus::INVALID_STOPPED;
            }

            if ($account->account_status == AccountStatus::SUSPEND) {
                $account->account_status = AccountStatus::SUSPEND_STOPPED;
            }

            if($account->wait_restarting) {
                $account->wait_restarting = 0;

                $account->restart();

                continue;
            }

            $account->api_server_ip = null;
            $account->save();

            $this->info('Account Stopped', [ 'acc' => $account->account_number, 'status' => $account->account_status->label() ]);
        }
    }
}
