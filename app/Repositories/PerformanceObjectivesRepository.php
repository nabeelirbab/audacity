<?php

namespace App\Repositories;

use App\Enums\ObjectiveStatus;
use App\Enums\PerformanceObjectiveType;
use App\Models\PerformanceStat;
use App\Models\PerformanceTarget;

class PerformanceObjectivesRepository
{

    private PerformanceTarget $target;
    private PerformanceStat $stat;
    /** @var array<PerformanceObjectiveType> $failedObjectives */
    private array $failedObjectives;
    /** @var array<string,ObjectiveStatus> $summary */
    private array $summary;
    private ObjectiveStatus $result = ObjectiveStatus::NONE;

    private function __construct(PerformanceTarget $target, PerformanceStat $stat)
    {
        $this->target = $target;
        $this->stat = $stat;
    }

    public static function make(PerformanceTarget $target, PerformanceStat $stat) : PerformanceObjectivesRepository {
        $o = new PerformanceObjectivesRepository($target, $stat);

        $o->check();

        return $o;
    }

    public function check() : ObjectiveStatus {

        if($this->result != ObjectiveStatus::NONE) {
            return $this->result;
        }

        $isFailed = false;

        //dd(PerformanceObjectiveType::cases());
        foreach( PerformanceObjectiveType::cases() as $type) {
            $this->summary[$type->name] = ObjectiveStatus::PASSED;
        }

        if( $this->stat->max_loss <= $this->target->max_loss) {
            $isFailed = true;
            $this->failedObjectives[] = PerformanceObjectiveType::MAX_LOSS;
            $this->summary[PerformanceObjectiveType::MAX_LOSS->name] = ObjectiveStatus::FAILED;
        }

        if( $this->stat->max_daily_loss <= $this->target->max_daily_loss) {
            $isFailed = true;
            $this->failedObjectives[] = PerformanceObjectiveType::MAX_DAILY_LOSS;
            $this->summary[PerformanceObjectiveType::MAX_DAILY_LOSS->name] = ObjectiveStatus::FAILED;
        }

        if( $this->target->max_trading_days > 0 && $this->stat->days_traded >= $this->target->max_trading_days ) {
            $isFailed = true;
            $this->failedObjectives[] = PerformanceObjectiveType::TRADING_DAYS;
            $this->summary[PerformanceObjectiveType::TRADING_DAYS->name] = ObjectiveStatus::FAILED;
        }

        if( $this->stat->profit < $this->target->profit) {
            $this->summary[PerformanceObjectiveType::PROFIT->name] = ObjectiveStatus::FAILED;
        }

        if( $this->target->check_hedging && $this->stat->hedging_detected) {
            $this->summary[PerformanceObjectiveType::HEDGING_DETECTED->name] = ObjectiveStatus::FAILED;
        }

        if( $this->target->check_sl && $this->stat->sl_not_used) {
            $this->summary[PerformanceObjectiveType::SL_NOT_USED->name] = ObjectiveStatus::FAILED;
        }

        if(!$isFailed
                && $this->stat->days_traded >= $this->target->min_trading_days
                && $this->stat->profit >= $this->target->profit
                && $this->stat->max_loss >= $this->target->max_loss
                && $this->stat->max_daily_loss >= $this->target->max_daily_loss ) {
            return $this->result = ObjectiveStatus::PASSED;
        }

        return $this->result = ($isFailed ? ObjectiveStatus::FAILED : ObjectiveStatus::UNKNOWN);
    }


    /** @return array<string, ObjectiveStatus> */
    public function getSummary() : array {
        return $this->summary;
    }

    /** @return array<PerformanceObjectiveType> */
    public function getFailedObjectives() : array {
        return $this->failedObjectives;
    }

    public function getObjectiveStatus(PerformanceObjectiveType $type) : ObjectiveStatus {
        return $this->summary[$type->name];
    }

    public function getResult() : ObjectiveStatus {
        return $this->result;
    }
}
