<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Enums\AccountStatus;
use App\Enums\MT4RunResultType;
use App\Helpers\MT4Commands;
use App\Models\Account;
use Carbon\Carbon;

class VerifyAccounts extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify accounts';

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
        $diff = config('copier.wait_to_verify');

        try {
            $accounts = Account
                ::with('broker_server')
                ->with('user:id,email')
                ->with('api_server')
                ->whereHas('broker_server', static function ($query) {
                    $query->api();
                })
                ->whereHas('api_server', static function ($query) {
                    $query->enabled();
                })
                ->where('account_status', AccountStatus::VERIFYING)
                ->where('updated_at','<',Carbon::now()->subSeconds($diff))
                ->get();

            foreach ($accounts as $account) {
                $ret = MT4Commands::check(
                    $account->api_server_ip,
                    $account->broker_server_name,
                    $account->account_number,
                    $account->password
                );

                //$this->info('Verifying account', ['acc' => $account->account_number]);

                $code = $ret->getCode();

                if ($code & MT4RunResultType::ACCOUNT_INVALID) {
                    $account->markInvalid($ret->getMessage());
                    continue;
                }

                if ($code & MT4RunResultType::FAILED_REPEATABLE) {
                    //$account->account_status = AccountStatus::PENDING;
                    //$account->last_error = $ret->message;

                    $this->info('Failed to verify account, repeating', [
                        'acc' => $account->account_number,
                        'msg' => $ret->getMessage(),
                    ]);

                    continue;
                }

                if ($code & MT4RunResultType::FAILED) {
                    $account->markInvalid($ret->getMessage());

                    $this->error('Failed to verify account. stopping', [
                        'acc' => $account->account_number,
                        'msg' => $ret->getMessage()
                    ]);
                    continue;
                }

                if ($code & MT4RunResultType::OK) {
                    $this->info('Account verified. OK', ['acc' => $account->account_number]);
                    $account->last_error = '';
                }
                $account->save();
            }
        } catch (\Exception $e) {
            $this->critical($e->getMessage());
        }
    }
}
