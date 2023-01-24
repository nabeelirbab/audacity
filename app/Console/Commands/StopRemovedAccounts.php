<?php

namespace App\Console\Commands;

use App\Models\Account;
use App\Jobs\ProcessRemovedAccount;
use App\Console\Commands\BaseCommand;

class StopRemovedAccounts extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:stopremoved';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop and clean up removed accounts';

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
        $accounts = Account::onlyTrashed()->where('processing', 0)->get();

        foreach ($accounts as $account) {
            /** @var Account $account */

            try {

                $account->processing = 1;
                $account->save();
                ProcessRemovedAccount::dispatch($account->id)->onQueue($account->getRemovingQueueName());

            } catch (\Exception $e) {

                $account->processing = 0;
                $account->save();

                $this->critical('Failed to stop removed account', [
                    'acc' => $account, 'ex' => $e->getMessage()
                ]);
            }
        }
    }
}
