<?php

namespace App\Jobs;

use App\Models\Account;
use App\Helpers\MT4Commands;
use App\Jobs\ShouldQueueBase;
use App\Enums\AccountStatus;
use App\Enums\MT4RunResultType;

final class ProcessPendingAccount extends ShouldQueueBase
{
    private $accountId;

    protected $signature = 'account:reload';

    public $timeout = 120;
    public $tries = 5;

    public function __construct(int $accountId)
    {
        $this->accountId = $accountId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var Account $account */
        $account = Account::with(['api_server', 'manager:id,api_token'])->find($this->accountId);

        if ($account == null || $account->processing == false) {
            $this->info('Pending account not found, or already processed', ['acc' => $this->accountId]);
            return;
        }

        if(empty($account->password)) {
            $this->error('Password is empty', [
                'id' => $this->accountId,
                'ip' => $account->api_server_ip,
                'broker' => $account->broker_server_name,
                'acc' => $account->account_number
                ]);

            $account->account_status = AccountStatus::INVALID;
            $account->last_error = 'Password is empty';
            $account->processing = false;
            $account->save();

            return;
        }

        //$maxProcessing = $account->api_server->max_processing_accounts;
        //$key = $account->api_server_ip;

        //Redis::funnel($key)->limit($maxProcessing)->then(function () use($account) {

            $this->info('Starting account', [
                'id' => $this->accountId,
                'ip' => $account->api_server_ip,
                'broker' => $account->broker_server_name,
                'acc' => $account->account_number,
                'pwd' => substr($account->password, 0, 2)
            ]);

            $api_token = $account->manager->api_token;

            $ret = MT4Commands::run(
                $account->api_server_ip, $account->broker_server_name,
                $api_token, $account->jfx_mode, $account->collect_equity,
                $this->accountId, $account->account_number, $account->password);

            $code = $ret->getCode();

            if ($code == MT4RunResultType::OK) {
                $account->account_status = AccountStatus::VERIFYING;
                $account->last_error = '';
                $this->info('Account started, verifying...',
                    ['id' => $this->accountId, 'acc' => $account->account_number]);
            }

            if (($code & MT4RunResultType::FAILED) == MT4RunResultType::FAILED) {
                $account->account_status = AccountStatus::INVALID;
                $account->last_error = $ret->getMessage();
            }

            if (($code & MT4RunResultType::FAILED_W_ALERT ) == MT4RunResultType::FAILED_W_ALERT) {
                $this->error('Account failed to start.', [
                    'id' => $this->accountId,
                    'acc' => $account->account_number,
                    'msg' => $ret->getMessage(),
                    'ip' => $account->api_server_ip
                ]);
            }

            if (($code & MT4RunResultType::FAILED_REPEATABLE) == MT4RunResultType::FAILED_REPEATABLE) {
                $account->account_status = AccountStatus::PENDING;
                $account->last_error = $ret->getMessage();

                $this->info('Account failed to start, repeating', [
                    'id' => $this->accountId,
                    'acc' => $account->account_number,
                    'ip' => $account->api_server_ip,
                    'msg' => $ret->getMessage()
                ]);
            }

            $account->processing = false;
            $account->save();
        //}, function () {
          //  return $this->release(120);
        //});
    }

    public function failed($exception)
    {
        $this->critical($exception);
    }

    public function tags()
    {
        return ['connecting', 'account:'.$this->accountId];
    }
}
