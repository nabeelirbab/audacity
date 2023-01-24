<?php

namespace App\Models;

use App\Enums\ChallengeStatus;
use App\Models\Performance;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Challenge extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'performance_challenges';

    protected $fillable = ['status', 'price'];

    protected $casts = [
        'status' => ChallengeStatus::class
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function performance() : BelongsTo
    {
        return $this->belongsTo(Performance::class);
    }

    public function performanceWithObjectives() : BelongsTo
    {
        return $this->belongsTo(PerformanceWithObjectives::class, 'performance_id');
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function stat() : BelongsTo
    {
        return $this->belongsTo(PerformanceStat::class, 'performance_id');
    }

    public function plan() : BelongsTo
    {
        return $this->belongsTo(PerformancePlan::class, 'performance_plan_id');
    }

}
