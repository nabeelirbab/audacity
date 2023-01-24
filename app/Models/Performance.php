<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PerformanceStatus;
use App\Models\Account;
use App\Models\AccountStat;
use App\Models\Order;
use App\Models\PerformancePlan;
use App\Models\PerformanceStat;
use App\Models\PerformanceTarget;
use App\Models\User;
use App\Repositories\PerformanceObjectivesRepository;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Performance extends Model
{
    use HasDateTimeFormatter;
    use HasRelationships;

    protected $table = 'performances';
    protected $casts = [
        'started_at' => 'datetime:Y-m-d',
        'status' => PerformanceStatus::class,
    ];

    private $ordersClosedCache = null;
    private ?PerformanceObjectivesRepository $objectives = null;

    public function target() : HasOne
    {
        return $this->hasOne(PerformanceTarget::class, 'performance_id');
    }

    public function plan() : BelongsTo
    {
        return $this->belongsTo(PerformancePlan::class,'performance_plan_id');
    }

    public function stat() : HasOne
    {
        return $this->hasOne(PerformanceStat::class, 'performance_id');
    }

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function account_stat() : HasOneThrough
    {
        return $this->hasOneThrough(AccountStat::class, Account::class, 'id', 'account_number', 'account_id', 'account_number');
    }

    public function scopeActive($query) {
        return $query->whereIn('status', [PerformanceStatus::ACTIVE]);
    }

    public function scopeEnded($query) {
        return $query->whereIn('status', [PerformanceStatus::ENDED_FAILED, PerformanceStatus::ENDED_PASSED]);
    }

    public function instrumentsDistribution() {
        return Order
            ::whereIn('type', [OrderType::BUY, OrderType::SELL])
            ->where('account_number', $this->account_number)
            ->groupBy('symbol')
            ->select(DB::raw('count(ticket) as cnt, symbol'))
            ->pluck('cnt', 'symbol')
            ->toArray();
    }

    public function isAllOrdersUploaded() : bool
    {
        return !$this->orders()->whereStatus(OrderStatus::CLOSED)->whereNull('time_close')->exists();
    }

    public function orders()
    {
        return Order
            ::where('account_number', $this->account_number)
            ->whereIn('type', [OrderType::BUY, OrderType::SELL]);
    }

    public function ordersAll()
    {
        return Order
            ::where('account_number', $this->account_number)
            ->whereIn('type', [OrderType::BUY, OrderType::SELL, OrderType::BALANCE]);
    }

    public function ordersAllClosed()
    {
        return $this->ordersAll()
            ->whereStatus(OrderStatus::CLOSED)
            ->orderBy('time_close', 'ASC');
    }

    public function ordersClosed()
    {
        if($this->ordersClosedCache != null) {
            return $this->ordersClosedCache;
        }

        return $this->ordersClosedCache = $this->orders()
            ->whereStatus(OrderStatus::CLOSED)
            ->orderBy('time_close', 'ASC');
    }

    public static function createForAccountPlanKey($account, $planKey) {
        $plan = PerformancePlan::where('key', $planKey)->first();

        if(!$plan)
            return null;

        self::createForAccountPlan($account, $plan);
    }

    public static function createForAccountPlanId(Account $account, int $planId) : ?Performance {
        $plan = PerformancePlan::find($planId);

        if(!$plan)
            return null;

        return self::createForAccountPlan($account, $plan);
    }

    public static function createForAccountPlan(Account $account, PerformancePlan $plan) : Performance
    {

        $p = Performance::make($account, $plan->id);

        PerformanceTarget::make($p->id, $plan);

        PerformanceStat::make($p->id);

        $account->collect_equity = true;
        $account->save();

        return $p;
    }

    public static function generateSlug() : string {
        return Str::random(12);
    }

    public function getObjectives() : ?PerformanceObjectivesRepository {

        if(is_null($this->objectives)) {
            $this->objectives = PerformanceObjectivesRepository::make($this->target, $this->stat);
        }

        return $this->objectives;
    }

    public static function make(Account $account, int $planId) : Performance
    {
        $p = new static();

        $p->manager_id = $account->manager_id;
        $p->user_id = $account->user_id;
        $p->account_id = $account->id;
        $p->account_number = $account->account_number;
        $p->slug = self::generateSlug();

        $p->performance_plan_id = $planId;

        $p->save();

        return $p;
    }

}