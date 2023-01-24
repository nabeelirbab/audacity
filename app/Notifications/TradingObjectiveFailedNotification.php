<?php

namespace App\Notifications;

use App\Enums\PerformanceObjectiveType;
use App\Mail\TradingObjectiveFailedMail;
use App\ManagerMailer;
use App\ManagerTemplateMailable;
use App\Models\Account;
use App\Models\PerformancePlan;
use App\Models\PerformanceStat;
use App\Models\PerformanceTarget;
use App\UserManagerNotifier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TradingObjectiveFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Account $account;
    public PerformancePlan $plan;
    public PerformanceStat $stat;
    public PerformanceTarget $target;
    /** @var array<PerformanceObjectiveType> $objectiveTypes */
    public array $objectiveTypes;
    public int $manager_id;

    private PerformanceObjectiveType $firstFailedType;

    /** @param array<PerformanceObjectiveType> $objectiveTypes */
    public function __construct(array $objectiveTypes, PerformanceTarget $target, PerformanceStat $stat,
       PerformancePlan $plan, Account $account, int $manager_id)
    {
        $this->account = $account;
        $this->plan = $plan;
        $this->target = $target;
        $this->stat = $stat;
        $this->objectiveTypes = $objectiveTypes;
        if(count($objectiveTypes) > 0 )
            $this->firstFailedType = $objectiveTypes[0];
        $this->manager_id = $manager_id;

        $this->onQueue('performances');
    }

    public function via($notifiable)
    {
        return [ManagerMailer::class, 'database', UserManagerNotifier::class];
    }

    public function toManagerMailer($notifiable) : ManagerTemplateMailable
    {
        return (
            new TradingObjectiveFailedMail(
                $this->account->account_number,
                $this->firstFailedType->label(),
                $this->plan->title,
                $this->stat->max_loss,
                $this->stat->max_daily_loss,
                $this->stat->profit,
                $this->stat->days_traded,
                $this->target->max_loss,
                $this->target->max_daily_loss,
                $this->target->profit,
                $this->target->min_trading_days,
                $this->target->max_trading_days,
                $this->manager_id
            )
        );
    }

    public static function getIcon() {
        return 'fa fa-bug';
    }

    public function toDatabase($notifiable) {
        return ['message' => __('notification.user.failed_objective',
            [
                'failed_objective' => $this->firstFailedType->label(),
                'plan' => $this->plan->title,
                'account' => $this->account->account_number
            ]
        )];
    }

    public function toUserManagerNotifier($notifiable) {
        return __('notification.manager.failed_objective',
        [
            'failed_objective' => $this->firstFailedType->label(),
            'plan' => $this->plan->title,
            'account' => $this->account->account_number
        ]);
    }
}