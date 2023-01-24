<?php

namespace App\Models;

use App\Models\Performance;
use App\Models\PerformancePlan;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class PerformanceTarget extends Model
{
    use HasDateTimeFormatter;

    protected $primaryKey = 'performance_id';

    protected $table = 'performance_targets';

    public function performance() : BelongsTo
    {
        return $this->belongsTo(Performance::class, 'performance_id');
    }

    public function plan() : HasOneThrough
    {
        return $this->hasOneThrough(PerformancePlan::class, Performance::class, 'id', 'id', 'performance_id', 'performance_plan_id');
    }

    public static function make( int $performanceId, PerformancePlan $plan) : PerformanceTarget
    {
        $t = new static();

        $t->performance_id = $performanceId;
        $t->min_trading_days = $plan->min_trading_days;
        $t->max_trading_days = $plan->max_trading_days;
        $t->check_hedging = $plan->check_hedging->value;
        $t->check_sl = $plan->check_sl->value;

        $t->save();

        return $t;
    }

    public function reset() {

        $plan = $this->plan()->first();

        $this->min_trading_days = $plan->min_trading_days;
        $this->max_trading_days = $plan->max_trading_days;
        $this->check_hedging = $plan->check_hedging->value;
        $this->check_sl = $plan->check_sl->value;

        $this->save();

        return $this;
    }
}
