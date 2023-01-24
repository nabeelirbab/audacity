<?php
/*
namespace App\Console\Commands\MT4Manager;

use App\Models\Account;
use App\Enums\AccountStatus;
use App\Models\User;
use Swagger\Client\Api\DefaultApi;
use Swagger\Client\Api\UserApi;

use App\Models\BrokerManager;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Swagger\Client\Configuration;

class UsersRefresh extends Command
{
    protected $signature = 'mt4manager:users_refresh';

    protected $description = 'Sync users in mt4 server and db';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {

            $managers = BrokerManager::enabled()->get();

            foreach ($managers as $manager) {
                $config = new Configuration();
                $config->setHost($manager->api_host);
                $init = new DefaultApi(null, $config);
                $ret = $init->initGet($manager->ip, $manager->login, $manager->password);

                if($ret->getCode() === 200)
                    $token = $ret->getToken();
                else
                    continue;

                $userApi = new UserApi(null, $config);
                $ret = $userApi->usersGet($token);

                $accountNumbers = $ret->getData();

                foreach($accountNumbers as $accountNumber) {
                    $account = Account::whereAccountNumber($accountNumber)->first();

                    if(!$account) {
                        $ret = $userApi->userUserLoginGet($token, $accountNumber);

                        if($ret->getEnabled() === 0) {
                            continue;
                        }

                        $user = User::firstOrCreate(
                            ['username' => $accountNumber],
                            [
                                'username' => $accountNumber,
                                'email' => $ret->getEmail(),
                                'name' => $ret->getName(),
                                'password' => empty($ret->getPassword() ) ? $accountNumber : $ret->getPassword(),
                                'manager_id' => $manager->manager_id,
                            ]
                        );

                        Account::updateOrCreate(
                            ['account_number' => $accountNumber],
                            [
                                'account_number' => $accountNumber,
                                'broker_server_id' => $manager->broker_server_id,
                                'user_id' => $user->id,
                                'manager_id' => $manager->manager_id,
                                'creator_id' => $manager->manager_id,
                                'account_status' => AccountStatus::ONLINE,
                            ]
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
*/