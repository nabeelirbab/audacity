<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Enums\ChallengeStatus;
use App\Models\Account;
use App\Models\Performance;
use App\Models\Challenge;
use App\Models\PerformancePlan;
use App\Models\User;
use App\Models\UserBrokerServer;
use App\Notifications\ChallengeAccountGeneratedNotification;

class GenerateAccountForChallenge extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:generate_account';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate Challenge Order - generate mt4 account';

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
            $orders = Challenge
                ::with(['plan'])
                ->whereStatus(ChallengeStatus::CONFIRMED)
                ->whereNull('performance_id')
                ->get();

            foreach ($orders as $order) {

                try {
                    $broker = UserBrokerServer
                        ::whereHas('broker_server', function($broker) {
                            return $broker->active();
                        })
                        ->whereUserId($order->manager_id)
                        ->default()
                        ->first();
                    $plan = PerformancePlan::find($order->performance_plan_id);
                    $user = User::find($order->user_id);

                    if(is_null($user)) {
                        throw new \Exception('User not found, id=', $order->user_id);
                    }

                    if(is_null($plan)) {
                        throw new \Exception('Plan not found, id=', $order->performance_plan_id);
                    }

                    if(is_null($broker)) {
                        throw new \Exception('Default Broker not found or not active');
                    }

                    $name = $user->name . ' ('.$plan->title.')';

                    $account = Account::CreateDemo($order->manager_id, $user->id, $broker,
                        $plan->initial_balance, $plan->leverage, $user->email, $name
                    );

                    $perf = Performance::createForAccountPlan($account, $plan);

                    $user->notify(new ChallengeAccountGeneratedNotification($order, $account, $user->manager_id));

                    $order->account_id = $account->id;
                    $order->performance_id = $perf->id;
                    $order->status = ChallengeStatus::ACTIVE;
                    $order->save();
                } catch (\Exception $e) {
                    $order->last_error = $e->getMessage();
                    $order->status = ChallengeStatus::ERROR;
                    $order->save();

                    $this->critical($e->getMessage());
                    echo $e->getMessage();
                }

            }
     }
}