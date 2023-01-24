<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Helpers\MT4Commands;
use App\Models\Account;
use App\Enums\AccountStatus;
use Illuminate\Support\Str;

class PingAccounts extends BaseCommand
{
    protected $signature = 'accounts:ping';

    protected $description = 'Ping accounts';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $diff = 300; //seconds

        try {

            $accounts = Account
                ::with('broker_server')
                ->with('api_server')
                ->whereHas('api_server', static function ($query) {
                    $query->enabled();
                })
                ->whereHas('broker_server', static function ($query) {
                    $query->api();
                })
                ->where('account_status', AccountStatus::ONLINE)
                ->selectRaw('*, TIMESTAMPDIFF(SECOND , accounts.updated_at, now() ) as diff')
                ->get();

            $accountsOnline = '';
            $data['channel'] = 'system';
            $data['command'] = 'get_online';
            $ws_host = config('funded.ws_host');
            $accountsOnline = MT4Commands::wsSendCommand($ws_host, \json_encode($data));

            foreach ($accounts as $account) {

                $is_restart = false;

                if ($account->diff >= $diff) {
                    $this->warning(
                        "Account is not updated db status more than $diff seconds. Restarting",
                        [
                            'ip' => $account->api_server_ip,
                            'account_number' => $account->account_number,
                            'broker' => $account->broker_server_name,
                            'diff' => $account->diff
                        ]
                    );
                    $is_restart = true;
                }

                if (Str::contains($accountsOnline, $account->account_number.';') == false) {
                    $this->warning("WS ping is false. Restarting", [
                        'ip' => $account->api_server_ip,
                        'account_number' => $account->account_number,
                        'broker' => $account->broker_server_name
                    ]);

                    $is_restart = true;
                }

                if ($is_restart) {
                    $account->restart();
                }
            }
        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();
        }
    }
}
