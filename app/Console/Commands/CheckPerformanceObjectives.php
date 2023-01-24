<?php

namespace App\Console\Commands;

use App\Console\Commands\BaseCommand;
use App\Enums\ObjectiveStatus;
use App\Enums\PerformanceStatus;
use App\Models\PerformanceWithObjectives;
use App\Notifications\TradingObjectiveFailedNotification;
use App\Notifications\PerformancePassedNotification;

use Carbon\Carbon;

class CheckPerformanceObjectives extends BaseCommand
{
    protected $signature = 'performance:check_objectives';

    protected $description = 'Check Performance Objectives';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $performances = PerformanceWithObjectives
                ::with(['stat', 'plan', 'target', 'user', 'account'])
                ->where('status', [PerformanceStatus::ACTIVE])
                ->get();

            foreach ($performances as $performance) {
                /** @var PerformanceWithObjectives $performance */
                $result = $performance->objectives->getResult();

                if($result == ObjectiveStatus::FAILED) {
                    $performance->user->notify(
                        new TradingObjectiveFailedNotification(
                            $performance->objectives->getFailedObjectives(),
                            $performance->target,
                            $performance->stat,
                            $performance->plan,
                            $performance->account,
                            $performance->user->manager_id
                        ));
                    $performance->status = PerformanceStatus::ENDED_FAILED;
                    $performance->ended_at = Carbon::now();
                    $performance->save();
                }

                if( $result == ObjectiveStatus::PASSED) {
                    $performance->user->notify(
                        new PerformancePassedNotification(
                            $performance->target,
                            $performance->stat,
                            $performance->plan,
                            $performance->account,
                            $performance->user->manager_id
                        ));

                    $performance->ended_at = Carbon::now();
                    $performance->status = PerformanceStatus::ENDED_PASSED;
                    $performance->save();
                }
            }

        } catch (\Exception $e) {
            $this->critical($e->getMessage());
            echo $e->getMessage();
        }
    }
}