<?php

namespace App\Listeners;

use App\Events\OrderStateChanged;
use App\Models\Account;
use App\Models\CopierSignal;
use App\Models\CopierSignalFollower;
use App\Models\User;
use App\Notifications\AccountFollowerOrderStateChangedNotifcation;
use App\Notifications\FollowerOrderStateChangedNotifcation;
use App\Notifications\SignalOrderStateChangedNotifcation;
use Illuminate\Support\Facades\Log;

class OrderStateChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(OrderStateChanged $event)
    {
        try {
            $order = $event->order;

            CopierSignal::with(['senders','followers_settings' => function($query) {
                return $query->whereEmailEnabled(1);
            }])->whereHas('senders', function($query) use($event) {
                return $query->whereAccountId($event->order->account_id);
            })->get()->each(function(CopierSignal $signal) use($event) {

                $signal->notify(new SignalOrderStateChangedNotifcation(
                    $event->order,
                    $event->state
                ));

                $signal->followers_settings->each(function(CopierSignalFollower $follower) use($event, $signal) {
                    $follower->notify(new FollowerOrderStateChangedNotifcation(
                        $event->order,
                        $event->state,
                        $signal->manager_id,
                        $signal->title
                     ));
                });

            });

            $account = Account::find($order->account_id);

            if($account) {
                $account->followers()->get(['id','manager_id'])->each(function(User $user) use($event, $account) {
                    $user->notify(new AccountFollowerOrderStateChangedNotifcation(
                        $event->order,
                        $event->state,
                        $user->manager_id,
                        $account->title
                    ));
                });
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            Log::error($e);
        }
    }
}
