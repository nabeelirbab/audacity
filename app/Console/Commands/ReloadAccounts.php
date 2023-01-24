<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Enums\AccountStatus;
use App\Jobs\ProcessPendingAccount;
use App\Models\Account;
use App\Models\ApiServer;
use Illuminate\Support\Facades\Queue;

class ReloadAccounts extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:reload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reload pending accounts';

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

            $accounts = Account
                ::where('account_status', AccountStatus::PENDING)
                ->where('processing', 0)
                ->get();

            foreach ($accounts as $account) {

                $server = ApiServer::getOneWithFreeSpace($account->manager_id);

                if(!$server) {
                    $account->markOrfant();
                    continue;
                } else {
                    if($account->api_server_ip != $server->ip) {
                        $account->old_api_server_ip = $account->api_server_ip;
                    }
                    $account->api_server_ip = $server->ip;
                }

//                $freeSpace = $server->max_accounts - $server->accounts_count;

                // if(Queue::size($account->getConnectingQueueName()) >= $freeSpace)
                //     continue;

                $account->processing = 1;
                $account->save();

                ProcessPendingAccount::dispatch($account->id)->onQueue($account->getConnectingQueueName());
            }

        } catch (\Exception $e) {
            $this->critical($e);
        }
    }
}
